#include <cstdlib>
#include <cstdio>
#include <cstring>

void set_bit(unsigned long *x, int bit, int value);
int get_bit(unsigned long x, int bit);
void print_long64(long xx);

int main(int argc, char **argv) {
  FILE *ff;
  int tree_count = 0;
  char ch;
  char ss[2048];
  //char questions[256];
  unsigned long questions = 0;
  unsigned long questions_new = 0;
  int valid_count = 0;
  int c;
  int count, max, min;
  int question_count;
  int total = 0;
  int seat;
  int digit;
  int n;
  int strike = 0;
  int done = 0;
  max = -1;
  min = 4096;
  count = 0;
  int numbers[4096];
  ff = fopen("input", "r");
  for(int i=0; i<4096; i++) {
    if(feof(ff)) {
      break;
    }
    fscanf(ff, "%d\n", &digit);
    numbers[i] = digit;
    count++;
  }

  for(int i=0; i < count; i++) {
    printf("%d\n", numbers[i]);
  }

  for(int i=0; i < count; i++) {
    for(int j=i+1; j < count; j++) {
      for(int k=j+1; k < count; k++) {
        if(numbers[i] + numbers[j] + numbers[k] == 2020) {
          printf("%d + %d + %d = 2020\n", numbers[i], numbers[j], numbers[k]);
	  printf("%d x %d x %d = %d\n", numbers[i], numbers[j],
	    numbers[k], numbers[i]*numbers[j]*numbers[k]);
        }
      }
    }
  }

  fclose(ff);
}

// 0 indexed from the right
void set_bit(unsigned long *x, int bit, int value) {
  if (value != 0 && value != 1) {
    printf("ERROR\n");
    exit(0);
  }
  unsigned long xx = 0x1;
  xx = xx << bit;
  if (value == 1) {
    *x = *x | xx;
  }
  else {
    xx = ~xx;
    *x = *x & xx;
  }
}

int get_bit(unsigned long x, int bit) {
  x = x >> bit;
  x = x & 0x1ul;
  return x;
}

void print_long64(long xx) {
  long tt;
  for(int i=63; i>=0; i--) {
    tt = xx >> i;
    tt &= 0x1ul;
    printf("%ld", tt);
  }
}




