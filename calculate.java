import java.sql.*;
import java.util.ArrayList;

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

		public transactionHeadNode() {}
		
		public transactionHeadNode(String cardID, boolean isPrepay, int transCount, int personType, int personNum)
		{
			this.cardID = cardID;
			this.isPrepay = isPrepay;
			this.transCount = transCount;
			this.personType = personType;
			this.personNum = personNum;
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
				{
					transactionHeadNode tempH = new transactionHeadNode(cardID, isPrepay, 0, personType, personNum);
					transactionUnitNode tempU = new transactionUnitNode(changeFee, taggingDateTime, busstop, busline, 0);
					tempH.unitNode = tempU;
					tempU.headNode = tempH;
					tempH.nextCard = t.nextCard;
					t.nextCard = tempH;
					return;
				}
				else if (t.nextCard.cardID.compareTo(cardID) == 0)
				{
					// 환승 승차 태그 입력
					if (t.nextCard.transCount == transCount - 1 && t.nextCard.unitNode.offTaggingDateTime != null)
					{
						t = t.nextCard;
						transactionUnitNode tempU = new transactionUnitNode(changeFee, taggingDateTime, busstop, busline, transCount);
						tempU.nextBoard = t.unitNode;
						t.unitNode = tempU;
						tempU.headNode = t;
						t.transCount++;
						return;
					}
					// 하차 태그 입력
					else if (t.nextCard.transCount == transCount && t.nextCard.unitNode.offTaggingDateTime == null)
					{
						t = t.nextCard;
						t.unitNode.offTaggingDateTime = taggingDateTime;
						t.unitNode.offBusstop = busstop;
						NodetoPrint.add(t.unitNode);
						return;
					}
					// 기타 입력
					else
					{
						System.out.println("exception: hacha no tagging");
						return;
					}
				}
				else t = t.nextCard;
			}
		}
	}

	static transactionSet tSet = new transactionSet();
	static ArrayList<transactionUnitNode> NodetoPrint = new ArrayList<transactionUnitNode>();

	public static void main(String[] args)
	{
		try 
		{
			Connection con = null;
			con = DriverManager.getConnection("jdbc:mysql://54.178.195.175 /software_application_2014_1", "parksoyoon", "qqqq");
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
				i++;
			}
			System.out.println("finished");

		} catch (SQLException sqex) {
			System.out.println("SQLException: " + sqex.getMessage());
			System.out.println("SQLState: " + sqex.getSQLState());
		}
	}
}
