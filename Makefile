all:
#	javac -encoding ms949 nowtest.java
	javac -encoding utf-8 calculate.java
parksoyoon:
	java nowtest
kimtaehoon:
	java calculate
run_ubuntu:
	java -classpath ".:/usr/share/java/mysql.jar/" calculate
run_winxp:
	java -classpath ".;C:\p\mysql-connector-java-5.1.29\mysql-connector-java-5.1.29-bin.jar" calculate
clean:
	rm -rf *.class
clean_win:
	del *.class