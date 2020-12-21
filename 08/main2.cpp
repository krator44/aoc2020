#include <cstdlib>
#include <cstdio>
#include <cstring>

void set_bit(unsigned long *x, int bit, int value);
int get_bit(unsigned long x, int bit);
void print_long64(long xx);


enum op_t { nop = 0, acc, jmp };

struct inst_t {
  op_t op;
  int x;
  int visited;
};

void reset_code();
void flip_inst(int num);
inst_t decode_inst(char *s, int digit);
bool execute_code();
void print_code();
void print_inst(inst_t inst);

int xx = 0;
int ip = 0;
inst_t code[4096];
int count;
int exec_count;

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
  count = 0;
  int numbers[4096];
  ff = fopen("input", "r");
  for(int i=0; i<4096; i++) {
    if(feof(ff)) {
      break;
    }
    fscanf(ff, "%s %d\n", ss, &digit);
    code[i] = decode_inst(ss, digit);
    count++;
  }

  fclose(ff);
  print_code();  
  bool success = false;
  for (int i=0; i < count; i++) {
    if(code[i].op == acc) {
      continue;
    }
    reset_code();
    flip_inst(i);
    success = execute_code();
    if (success) {
      digit = i;
      break;
    }
    flip_inst(i);
  }
  //execute_code();
  printf("..xx xx xx xx..\n");
  printf("..xx xx xx xx..\n");
  print_code();
  printf("halt at count %d\n", exec_count);
  printf("flipped %d\n", digit);
  printf("instruction %d visited twice\n", ip);
  printf("accumulator %d\n", xx);
}

void reset_code() {
  for(int i = 0; i < count; i++) {
    code[i].visited = 0;
  }
}

void flip_inst(int num) {
  switch(code[num].op) {
    case nop:
    code[num].op = jmp;
    break;
    case jmp:
    code[num].op = nop;
    break;
    case acc:
    default:
    printf("ERROR: flip_inst\n");
    exit(0);
  }
}

bool execute_code() {
  inst_t *tt;
  exec_count = 1;
  ip = 0;
  xx = 0;
  for (;;) {
    if (ip >= count) {
//      printf("ERROR: code overrun\n");
//      exit(0);
      printf("program terminated\n");
      return true;
    }
    tt = &code[ip];
    if(tt->visited != 0) {
      printf("program doesnt terminate\n");
      return false;
    }
    tt->visited = exec_count;
    switch(tt->op) {
      case nop:
        ip++;
      break;
      case acc:
        xx += tt->x;
	ip++;
      break;
      case jmp:
        ip += tt->x;
      break;
      default:
      printf("ERROR\n");
      exit(0);
    }
    exec_count++;
  }
}

inst_t decode_inst(char *s, int digit) {
  inst_t inst;
  if(strncmp(s, "nop", 3) == 0) {
    inst.op = nop;
  }
  else if(strncmp(s, "acc", 3) == 0) {
    inst.op = acc;
  }
  else if(strncmp(s, "jmp", 3) == 0) {
    inst.op = jmp;
  }
  else {
    printf("ERROR\n");
    exit(0);
  }
  inst.x = digit;
  inst.visited = false;
  return inst;
}

void print_code() {
  for(int i=0; i < count; i++) {
    printf("%4d ", i);
    print_inst(code[i]);
  }
  printf("total %d instructions\n", count);
}

void print_inst(inst_t inst) {
  switch(inst.op) {
    case nop:
    printf("nop");
    break;
    case acc:
    printf("acc");
    break;
    case jmp:
    printf("jmp");
    break;
    default:
    printf("ERROR\n");
    exit(0);
  }
  printf(" %7d", inst.x);
  if (inst.visited != 0) {
    printf(" \t%7d", inst.visited);
  }
  printf("\n");
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




