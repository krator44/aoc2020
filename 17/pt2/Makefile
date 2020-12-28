
all: xx

xx: pocket.o main.o
	g++ -o xx pocket.o main.o

pocket.o: pocket.cpp pocket.h
	g++ -c pocket.cpp

main.o: main.cpp pocket.h
	g++ -c main.cpp

clean:
	rm -fv *.o
	rm -fv xx


