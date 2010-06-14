OBJECTS = TeamMaker.o Globals.o
TeamMaker: $(OBJECTS)
	$(CC) $(CFLAGS) $(OBJECTS) -o TeamMaker -lstdc++
TeamMaker.o: TeamMaker.cpp TeamMaker.h Team.h Response.h Student.h WeightInfo.h XMLFile.h Globals.h StringParser.h
	$(CC) $(CFLAGS) -c TeamMaker.cpp
Globals.o: Globals.cpp Globals.h
	$(CC) $(CFLAGS) -c Globals.cpp
