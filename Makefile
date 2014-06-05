all:
#	javac -encoding ms949 nowtest.java
	javac calculate.java
parksoyoon:
	java nowtest
kimtaehoon:
	java calculate
run_ubuntu:
	java -classpath ".:/usr/share/java/mysql.jar/" calculate
clean:
	rm -rf *.class
