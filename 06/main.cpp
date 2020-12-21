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
  ff = fopen("input", "r");
  for(;;) {
    count++;
    strike = 0;
    questions = 0x03ffffff;
    questions_new = 0;
    for(;;) {
      c = fgetc(ff);
      //printf("ch %c\n", c);
      //print_long64(questions);
      //printf("\n");
      //print_long64(questions_new);
      //printf("\n");
      if (feof(ff)) {
        done = 1;
	// group done
	//printf("group done\n");
	//questions &= questions_new;
        //break;
      }
      if (done || c == '\n') {
	if (strike > 0) {
	  // group done
	  printf("group done xx\n");
	  break;
	}
	else {
          // person done
	  //printf("person done\n");
	  questions &= questions_new;
	  questions_new = 0;
          strike++;
	  //printf("strike %d\n", strike);
          //print_long64(questions);
          //printf("\n");
          //print_long64(questions_new);
          //printf("\n");
	}
	//printf("continue\n");
	continue;
      }
      else {
        set_bit(&questions_new, c-'a', 1);
	strike = 0;
	//printf("questions_new\n");
        //print_long64(questions_new);
	//printf("\n");
      }
    }
    // count questions
    question_count = 0;
    for(int i=0; i<=25; i++) {
      question_count += get_bit(questions, i); 
    }
    total += question_count;
    printf("person %d questions %d\n", count, question_count);
    if (done) {
      break;
    }
  }
  //printf("count = %d\n", count);
  fclose(ff);
  printf("total count %d\n", total);
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




