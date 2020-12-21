#include <cstdlib>
#include <cstdio>
#include <cstring>

int main(int argc, char **argv) {
  FILE *ff;
  int tree_count = 0;
  char ch;
  char ss[2048];
  char seats[2048];
  int valid_count = 0;
  int c;
  int count, max, min;
  int seat;
  int digit;
  int n;
  ff = fopen("input", "r");
  max = -1;
  min = 4096;
  count = 0;
  for(int i=0; i < 2048; i++) {
    seats[i] = 0;
  }
  for(;;) {
    fgets(ss, 2048, ff);
    if (feof(ff)) {
      break;
    }
    count++;
    seat = 0;
    n = strlen(ss);
    ss[n-1] = 0;
    for (int i=0; i < 10; i++) {
      switch(ss[i]) {
        case 'B':
	case 'R':
	  digit = 1;
	  break;
	case 'F':
	case 'L':
	  digit = 0;
	  break;
	default:
	  printf("ERROR\n");
	  exit(0);
      }
      digit = digit << (9-i);
      seat = seat | digit;
    }
    if (seat > max) {
      max = seat;
    }
    if (seat < min) {
      min = seat;
    }
    seats[seat] = 1;
    printf("%s %d\n", ss, seat);
  }
  printf("min seat = %d\n", min);
  printf("max seat = %d\n", max);
  printf("count = %d\n", count);
  fclose(ff);

  for (int i=min; i<=max; i++) {
    if (seats[i] == 0) {
      printf("seat %d is open\n", i);
    }
  }
}






