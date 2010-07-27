OBJECTS = TeamMaker.o Globals.o
TeamMaker: $(OBJECTS)
	ln -sf `g++ -print-file-name=libstdc++.a`
	$(CC) $(CFLAGS) $(OBJECTS) -o TeamMaker -static -static-libgcc -L. -lstdc++ -lm # check http://www.trilithium.com/johan/2005/06/static-libstdc/
TeamMaker.o: TeamMaker.cpp TeamMaker.h Team.h Response.h Student.h WeightInfo.h XMLFile.h Globals.h StringParser.h
	$(CC) $(CFLAGS) -c TeamMaker.cpp
Globals.o: Globals.cpp Globals.h
	$(CC) $(CFLAGS) -c Globals.cpp
clean:
	rm -f *.o
	rm -f TeamMaker

