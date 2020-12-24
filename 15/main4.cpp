#include <cstdlib>
#include <cstdio>
#include <cstring>

const int TABLE_SIZE = 40000000;
const int MAX_COUNT = 30000000;

int main(int argc, char **argv);
void reset_state();
void iterate_sequence();

long recent[TABLE_SIZE];
long count=1;
long most_recent=-1;
long n;

int main(int argc, char **argv) {
  FILE *ff;
  int c;

  reset_state();


  ff = fopen("input", "r");
  if (ff == 0) {
    printf("error: input file not found\n");
    exit(0);
  }
  for(;;) {
    if(fscanf(ff, "%ld", &n) != 1) {
      break;
    }
    //fscanf(ff, ",");
    if (n < 0 || n > 2048) {
      printf("error: %ld outside range [0,2048]\n", n);
      exit(0);
    }
    printf("%4ld %4ld\n", count, n);
    recent[n] = count;
    most_recent=n;
    count++; 

    c = fgetc(ff);
    //printf("ch %c\n", c);
    if(c=='\n') {
      iterate_sequence();
      reset_state();
    }
    else if (c==',') {
      continue;
    }
    else {
      break;
    }
  }
  if(count == 0) {
    printf("error: no numbers in input file\n");
    exit(0);
  }

  fclose(ff);
  return 0;
}

void reset_state() {
  n = 0;
  count=1;
  most_recent=-1;
  for(int i=0;i<TABLE_SIZE;i++) {
    recent[i]=-1;
  }
}

void iterate_sequence() {
  n = 0;
  for(;;) {
    if(count > MAX_COUNT) {
      break;
    }
    else if (count == MAX_COUNT) {
      printf("%4ld %4ld\n", count, n);
    }
    most_recent = n;
    if(recent[n] == -1) {
      n = 0;
    }
    else {
      if (recent[n] >= count) {
        printf("ERROR\n");
	exit(0);
      }
      if (recent[n] < 0) {
        printf("ERROR recent[%ld] == %ld\n", n, recent[n]);
	exit(0);
      }
      //printf("%ld - %ld = %ld\n", count, recent[n], count - recent[n]);
      n = count - recent[n];
    }
    //printf("recent[%ld] = %ld\n", most_recent, count);
    recent[most_recent] = count;
    count++;
  }
}




