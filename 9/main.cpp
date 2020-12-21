#include <cstdlib>
#include <cstdio>
#include <cstring>

void set_bit(unsigned long *x, int bit, int value);
int get_bit(unsigned long x, int bit);
void print_long64(long xx);

const int PREAMBLE_SIZE = 25;

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
  int max, min;
  int question_count;
  int total = 0;
  int seat;
  int digit;
  int n;
  int strike = 0;
  int done = 0;
  max = -1;
  min = 4096;
  unsigned long numbers[PREAMBLE_SIZE];
  int count = 0;
  int tt = 0;
  bool success = false;
  n = PREAMBLE_SIZE;
  ff = fopen("input", "r");
  for(;;) {
    if(feof(ff)) {
      break;
    }
    fscanf(ff, "%d\n", &digit);
    if (count >= n) {
      success = false;
      for(int i = 0; i < n; i++) {
        for (int j = i+1; j < n; j++) {
          if(numbers[i] + numbers[j] == digit) {
	    success = true;
	  }
        }
      }
      if (success == false) {
        printf("%d-th number fails. the number is %d\n", count, digit);
	break;
      }
    }
    numbers[tt] = digit;
    tt++;
    if (tt == n) {
      tt = 0;
    }
    count++;
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




