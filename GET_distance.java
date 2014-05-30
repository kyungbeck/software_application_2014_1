/*TEST CODE FOR CALCULATING DISTANCE 
  FROM START_BUSSTOP TO FINISH_BUSSTOP 
  USING ACCUMULATE DISTANCE */

	import java.sql.*;

	public class GET_distance {
           
		 protected String START_Busstop;
		 protected String FINISH_Busstop;
		 protected double start,finish;
		 protected double Dist;
	
		 //FUNCTION DISTANCE(START,FINISH) RETURNS DIST(TYPE DOUBLE) FROM START TO FINISH
		 
		 
    	public double Distance(String START, String FINISH){
	       this.START_Busstop=START;
	       this.FINISH_Busstop=FINISH;
	       
	       try {

				Connection con = null;
				con = DriverManager.getConnection("jdbc:mysql://54.178.195.175/software_application_2014_1",
						"parksoyoon", "qqqq");
				java.sql.Statement st = null;
				ResultSet rs = null;
				st = con.createStatement();
				rs = st.executeQuery("SELECT NO,BUSSTOP,ACCUMULATE FROM BUS_5511");
     
				//GET NO, BUSSTOP, ACCUMULATE DISTANCE COLUMNS OF TABLE BUS_5511 
				if (st.execute("SELECT NO,BUSSTOP,ACCUMULATE FROM BUS_5511")) {
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
					System.out.println("DISTANCE :");
					System.out.println(Dist);
				
			} catch (SQLException sqex) {
				System.out.println("SQLException: " + sqex.getMessage());
	    		System.out.println("SQLState: " + sqex.getSQLState());
			}
	       return Dist;
}
}