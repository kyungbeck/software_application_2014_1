import java.sql.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;

public class calculate {
	public static class transactionHeadNode
	{
		public String cardID;
		public boolean isPrepay;

		public transactionHeadNode nextCard;
		public transactionUnitNode unitNode;
		public int transCount;

		public int personType;
		public int personNum;

		public int total_fee; 
		public int max_basic_fee;
		public int sum_basic_fee;
		public double total_dist;

		public boolean isExpired;

		public transactionHeadNode() {}

		public transactionHeadNode(String cardID, boolean isPrepay, int transCount, int personType, int personNum)
		{
			this.cardID = cardID;
			this.isPrepay = isPrepay;
			this.transCount = transCount;
			this.personType = personType;
			this.personNum = personNum;

			this.total_fee = 0;
			this.max_basic_fee = 0;
			this.sum_basic_fee = 0;
			this.total_dist = 0;
			this.isExpired = false;
		}
	}

	public static class transactionUnitNode
	{
		public transactionUnitNode nextBoard;

		public int changeFee;
		public Timestamp onTaggingDateTime;
		public Timestamp offTaggingDateTime;

		public String onBusstop;
		public String offBusstop;
		public String busline;

		public int transCount;

		public int basicFee;

		public transactionHeadNode headNode;

		public transactionUnitNode() {}

		public transactionUnitNode(int changeFee, Timestamp onTaggingDateTime, String onBusstop, String busline, int transCount)
		{
			this.changeFee = changeFee;
			this.onTaggingDateTime = onTaggingDateTime;
			this.onBusstop = onBusstop;
			this.busline = busline;
			this.transCount = transCount;
		}
	}

	public static class transactionSet
	{
		public transactionHeadNode head = new transactionHeadNode();

		public void put(String cardID, boolean isPrepay, int transCount, int changeFee, Timestamp taggingDateTime, int personType, int personNum, String busstop, String busline)
		{
			transactionHeadNode t = this.head;
			while (true)
			{
				if (t.nextCard == null || t.nextCard.cardID.compareTo(cardID) > 0)
				{	// 1. 일반 승차 태그 (환승x)
					System.out.print("(" + cardID + ") 처음 승차 태그: ");
					int fee;
					int basic_fee = GET_basic_fee(busline, personType);
					fee = basic_fee;

					if (changeFee != fee)
						System.out.println("INCORRECT");
					else System.out.println("CORRECT");

					transactionHeadNode tempH = new transactionHeadNode(cardID, isPrepay, 0, personType, personNum);
					transactionUnitNode tempU = new transactionUnitNode(changeFee, taggingDateTime, busstop, busline, 0);
					tempH.max_basic_fee = basic_fee;
					tempH.sum_basic_fee = basic_fee;
					tempH.total_fee = fee;
					tempU.basicFee = basic_fee;

					tempH.unitNode = tempU;
					tempU.headNode = tempH;
					tempH.nextCard = t.nextCard;
					t.nextCard = tempH;
					return;
				}
				else if (t.nextCard.cardID.compareTo(cardID) == 0)
				{
					if (t.nextCard.unitNode.offTaggingDateTime != null)
					{
						if (transCount == 0)
						{	// 1. 일반 승차 태그 (환승x)
							t.nextCard.isExpired = true; // 이것말고는 위의 1과 동일

							System.out.print("(" + cardID + ") 처음 승차 태그: ");
							int fee;
							int basic_fee = GET_basic_fee(busline, personType);
							fee = basic_fee;

							if (changeFee != fee)
								System.out.println("INCORRECT");
							else System.out.println("CORRECT");

							transactionHeadNode tempH = new transactionHeadNode(cardID, isPrepay, 0, personType, personNum);
							transactionUnitNode tempU = new transactionUnitNode(changeFee, taggingDateTime, busstop, busline, 0);
							tempH.max_basic_fee = basic_fee;
							tempH.sum_basic_fee = basic_fee;
							tempH.total_fee = fee;
							tempU.basicFee = basic_fee;

							tempH.unitNode = tempU;
							tempU.headNode = tempH;
							tempH.nextCard = t.nextCard;
							t.nextCard = tempH;
							return;
						}

						else if (t.nextCard.transCount == transCount - 1)
						{	// 2. 환승 승차 태그 (선불카드 고려 x)
							System.out.print("(" + cardID + ") 환승 승차 태그: ");
							t = t.nextCard;
							int fee;
							int basic_fee = GET_basic_fee(busline, personType);
							int i = basic_fee - t.max_basic_fee;
							if (i > 0)
							{
								fee = i;
								t.max_basic_fee = basic_fee;
							}
							else fee = 0;

							if (changeFee != fee)
								System.out.println("INCORRECT");
							else System.out.println("CORRECT");

							t.sum_basic_fee += basic_fee;
							t.total_fee += fee;

							transactionUnitNode tempU = new transactionUnitNode(changeFee, taggingDateTime, busstop, busline, transCount);
							tempU.basicFee = basic_fee;
							tempU.nextBoard = t.unitNode;
							t.unitNode = tempU;
							tempU.headNode = t;
							t.transCount++;
							return;
						}

						else
						{	// 기타 입력
							System.out.println("exception occurred");
							return;
						}
					}

					else
					{
						if (t.nextCard.unitNode.busline.compareTo(busline) == 0 && t.nextCard.transCount == transCount)
						{	// 3. 하차 태그 (선불카드 고려 x)
							System.out.print("(" + cardID + ") 하차 태그: ");
							t = t.nextCard;
							int fee;
							double dist = GET_distance(busline, t.unitNode.onBusstop, busstop);
							t.total_dist += dist;

							if (t.transCount == 0) // 처음에 하차할 경우
								fee = 0;
							else
							{
								int fee_by_dist = ((((int)t.total_dist - 12) / 5) + 1) * 100 + t.max_basic_fee;
								if (fee_by_dist > t.sum_basic_fee)
									fee = t.sum_basic_fee - t.total_fee;
								else
									fee = fee_by_dist - t.total_fee;
							}

							t.total_fee += fee;

							if (changeFee != fee)
								System.out.println("INCORRECT");
							else System.out.println("CORRECT");

							t.unitNode.offTaggingDateTime = taggingDateTime;
							t.unitNode.offBusstop = busstop;
							t.unitNode.changeFee += fee;
							NodetoPrint.add(t.unitNode);
							return;
						}

						else if (transCount == 0)
						{	// 4. 하차 미태그 후 승차 태그
							System.out.print("(" + cardID + ") 하차 미태그 후 승차 태그: ");
							// 새 승차정보노드 추가
							int additional_fee = t.nextCard.unitNode.basicFee - t.nextCard.unitNode.changeFee;
							int fee;
							int basic_fee = GET_basic_fee(busline, personType);
							fee = basic_fee + additional_fee;

							if (changeFee != fee)
								System.out.println("INCORRECT");
							else System.out.println("CORRECT");

							transactionHeadNode tempH = new transactionHeadNode(cardID, isPrepay, 0, personType, personNum);
							transactionUnitNode tempU = new transactionUnitNode(changeFee, taggingDateTime, busstop, busline, 0);
							tempH.max_basic_fee = basic_fee;
							tempH.sum_basic_fee = basic_fee;
							tempH.total_fee = basic_fee;
							tempU.basicFee = basic_fee;

							tempH.unitNode = tempU;
							tempU.headNode = tempH;
							tempH.nextCard = t.nextCard;
							t.nextCard = tempH;

							// 기존 승차정보노드 갱신
							t = t.nextCard.nextCard;
							//				t.isAddFeeNode = true; (왜있는지 의문)
							t.isExpired = true;
							t.unitNode.offTaggingDateTime = t.unitNode.onTaggingDateTime;
							t.total_fee += additional_fee;
							NodetoPrint.add(t.unitNode);
							return;
						}

						else
						{	// 기타 입력
							System.out.println("exception occurred");
							return;
						}
					}
				}
				else t = t.nextCard;
			}
		}

		public void Cal()
		{
			transactionHeadNode t = this.head;
			HashMap<String, Integer> fee_by_busline = new HashMap<String, Integer>();

			while (true)
			{
				transactionHeadNode tempT = t;
				t = t.nextCard;
				if (t == null)
					break;

				Timestamp pivotTime = new Timestamp(0);
				long p = System.currentTimeMillis() - (1000 * 60 * 20);
				pivotTime.setTime(p);
				if (t.isExpired == true || t.unitNode.offTaggingDateTime.before(pivotTime))
				{	// 만료된 거래노드 정산
					int sum_basic_fee = t.sum_basic_fee;
					int total_fee = t.total_fee;
					int sum = 0;
					transactionUnitNode u = t.unitNode;
					while (true)
					{
						if (u == null)
							break;

						String bus = u.busline;
						int feeval;
						if (u.nextBoard == null)
							feeval = total_fee - sum;
						else
						{
							feeval = Math.round((float)(u.basicFee * total_fee) / sum_basic_fee);
							sum += feeval;
						}

						if (fee_by_busline.containsKey(bus))
							feeval += fee_by_busline.remove(bus);
						fee_by_busline.put(bus, feeval);

						u = u.nextBoard;
					}
					tempT.nextCard = tempT.nextCard.nextCard;
					t = tempT;
				}
			}

			String bus;
			int calculated;
			Iterator<String> it = fee_by_busline.keySet().iterator();
			System.out.println();
	//		System.out.println("<정산>");

			try {
				Connection con = null;
				con = DriverManager.getConnection("jdbc:mysql://localhost/library", "root", "qqqq");
				java.sql.Statement st = null;
				st = con.createStatement();

				while (it.hasNext())
				{
					bus = it.next();
					calculated = fee_by_busline.get(bus);
					System.out.print(bus + ": ");
					System.out.println(calculated + "원");
					st.execute("UPDATE company_calcul SET calculated = calculated + " + calculated + " WHERE company = (SELECT company FROM busline_company WHERE busline = '" + bus + "');");
				}
				System.out.println("inserting into 'company_calcul' finished");
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}

	public static int GET_basic_fee(String busline, int personType)
	{
		int basic_fee = 0;
		try {
			Connection con = null;
			con = DriverManager.getConnection("jdbc:mysql://localhost/library", "root", "qqqq");
			java.sql.Statement st = null;
			ResultSet rs = null;
			st = con.createStatement();

			st.execute("SELECT fee FROM fee_table NATURAL JOIN busline_bustype where busline = '" + busline + "' and persontype =" + personType + ";");
			rs = st.getResultSet();
			while (rs.next())
				basic_fee = rs.getInt(1);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return basic_fee;
	}

	public static double GET_distance(String busline, String START_Busstop, String FINISH_Busstop)
	{
		double start = 0;
		double finish = 0;
		double Dist = 0;
		try {

			Connection con = null;
			con = DriverManager.getConnection("jdbc:mysql://localhost/library", "root", "qqqq");
			java.sql.Statement st = null;
			ResultSet rs = null;
			st = con.createStatement();
			rs = st.executeQuery("SELECT NO,BUSSTOP,ACCUMULATE FROM BUS_" + busline + ";");

			//GET NO, BUSSTOP, ACCUMULATE DISTANCE COLUMNS OF TABLE BUS_5511 
			if (st.execute("SELECT NO,BUSSTOP,ACCUMULATE FROM BUS_" + busline +";")) {
				rs = st.getResultSet();
			}

			while (rs.next()) {
				//GET BUSSTOP NAME IN ORDER AND SAVE AS STR(TYPE STRING)
				String str = rs.getString("BUSSTOP");
				//IF STR == START_BUSSTOP NAME, GET ACCUMULATE DISTANCE AND SAVE AS START(TYPE DOUBLE)
				if (str.equals(START_Busstop)){
					start = rs.getDouble("ACCUMULATE");

				}
				//IF STR == FINISH_BUSSTOP NAME, GET ACCUMULATE DISTANCE AND SAVE AS FINISH(TYPE DOUBLE)
				else if (str.equals(FINISH_Busstop)){
					finish = rs.getDouble("ACCUMULATE");
				}
			}
			//DIST IS ABS VALUE OF SUBSTRACTION
			Dist=Math.abs(finish-start);
			//		System.out.println("DISTANCE :");
			//		System.out.println(Dist);

		} catch (SQLException sqex) {
			System.out.println("SQLException: " + sqex.getMessage());
			System.out.println("SQLState: " + sqex.getSQLState());
		}
		return Dist;
	}


	static transactionSet tSet = new transactionSet();
	static ArrayList<transactionUnitNode> NodetoPrint = new ArrayList<transactionUnitNode>();
	static ArrayList<transactionHeadNode> NodetoCal = new ArrayList<transactionHeadNode>();

	public static void main(String[] args)
	{
		try 
		{
			Connection con = null;
			con = DriverManager.getConnection("jdbc:mysql://localhost/library", "root", "qqqq");
			java.sql.Statement st = null;
			ResultSet rs = null;
			st = con.createStatement();

			st.execute("SELECT * FROM table_main;");
			rs = st.getResultSet();

			String cardID;
			boolean isPrepay; // 1:prepay, 2:hoobool
			int personType; // 1:adult, 2:adolescent, 3:child
			int changeFee;
			Timestamp taggingDateTime;
			int personNum;
			String busstop;
			String busline;
			int transCount;
			
	//		System.out.println("<정산 재작업(?)>");
			while (rs.next()) {
				cardID = rs.getString(2);
				if (rs.getInt(3) == 1)
					isPrepay = true;
				else isPrepay = false;
				personType = rs.getInt(4);
				changeFee = rs.getInt(5);
				taggingDateTime = rs.getTimestamp(6);
				personNum = rs.getInt(7);
				busstop = rs.getString(8);
				busline = rs.getString(9);
				transCount = rs.getInt(10);
				tSet.put(cardID, isPrepay, transCount, changeFee, taggingDateTime, personType, personNum, busstop, busline);
			}

			int i = 0;
			int cardType; // 1:prepay, 2:hoobool
			Timestamp onTime;
			Timestamp offTime;
			String onStop;
			String offStop;

			while (!NodetoPrint.isEmpty())
			{
				transactionUnitNode t = NodetoPrint.remove(i);
				transactionHeadNode h = t.headNode;

				cardID = h.cardID;
				if (h.isPrepay)
					cardType = 1;
				else cardType = 2;
				personType = h.personType;
				changeFee = t.changeFee;
				onTime = t.onTaggingDateTime;
				offTime = t.offTaggingDateTime;
				personNum = h.personNum;
				onStop = t.onBusstop;
				offStop = t.offBusstop;
				busline = t.busline;
				transCount = t.transCount; // h.transCount (X)

				String query = "INSERT INTO transactional_information (cardno, cardtype, persontype, changemoney, ridetagtime, offtagtime, personnumber, ridebusstop, offbusstop, busline, transnumber) values ('" + cardID + "', " + cardType + ", " + personType + ", " + changeFee + ", '" + onTime + "', '" + offTime + "', " + personNum + ", '" + onStop + "', '" + offStop + "', '" + busline + "', " + transCount + ");";
				st.execute(query);
			}
			System.out.println("inserting into 'transactional_information' finished");

			tSet.Cal();

		} catch (SQLException sqex) {
			System.out.println("SQLException: " + sqex.getMessage());
			System.out.println("SQLState: " + sqex.getSQLState());
		}
	}
}
