#include <cstdlib>
#include <cstdio>
#include <cstring>

int main(int argc, char **argv);
void reset_state();
void iterate_sequence();

int recent[2048];
int count=1;
int most_recent=-1;
int n;

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
    if(fscanf(ff, "%d", &n) != 1) {
      break;
    }
    //fscanf(ff, ",");
    if (n < 0 || n > 2048) {
      printf("error: %d outside range [0,2048]\n", n);
      exit(0);
    }
    printf("%4d %4d\n", count, n);
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
  for(int i=0;i<2048;i++) {
    recent[i]=-1;
  }
}

void iterate_sequence() {
  n = 0;
  for(;;) {
    if(count > 2020) {
      break;
    }
    else if (count == 2020) {
      printf("%4d %4d\n", count, n);
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
        printf("ERROR recent[%d] == %d\n", n, recent[n]);
	exit(0);
      }
      //printf("%d - %d = %d\n", count, recent[n], count - recent[n]);
      n = count - recent[n];
    }
    //printf("recent[%d] = %d\n", most_recent, count);
    recent[most_recent] = count;
    count++;
  }
}




