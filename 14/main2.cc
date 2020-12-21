#include <stdlib.h>
#include <cstdio>
#include <map>

using namespace std;

const int MEM_SIZE = 100000;
const unsigned long MASK_ONE = 0x0000000ffffffffful;
const unsigned long MASK_ZERO = 0x0ul;

void init();
void reset_mem();
void reset_mask();
void exec_mask(char *mask);
void exec_mem(long addr, long val);
void print_mask();
void print_long(long xx);
void print_long64(long xx);
void print_answer();

void mem_rec(long addr, long val, int init);

unsigned long bitmask_zero;
unsigned long bitmask_one;
unsigned long bitmask_x;
//long mem[MEM_SIZE];
map <long, long> mem;
char mask[2048];

int main(int argc, char **argv) {
  init();
  
  FILE *xx;
  xx = fopen("input", "r");


  char buf[2048];

  int num_scanned;

  long addr, val;

  printf("sizeof = %d\n", (int) sizeof(int));

  for (int i = 0; i < 2000; i++) {
    fgets(buf, 2048, xx);
    if (feof(xx)) {
      break;
    }
    num_scanned = sscanf(buf, "mask = %s\n", mask);
    if (num_scanned == 1) {
      printf("op mask        %s\n", mask);
      exec_mask(mask);
      continue;
    }
    num_scanned = sscanf(buf, "mem[%ld] = %ld\n", &addr, &val);
    if (num_scanned == 2) {
      printf("op mem %ld <= %ld\n", addr, val);
      exec_mem(addr, val);
      continue;
    }
    else {
      printf("ERROR\n");
      break;
    }
  }

  fclose(xx);

  print_mask();
  print_answer();
}

void init() {
  //reset_mem();
  reset_mask();
}

void reset_mem() {
  for(long i=0; i < MEM_SIZE; i++) {
    mem[i] = 0;
  }
}

void reset_mask() {
  bitmask_zero = 0x0000000ffffffffful;
  bitmask_one = 0x0000000000000000ul;
  bitmask_x = 0x0000000000000000ul;
}

void exec_mask(char *mask) {
  long tt;
  reset_mask();
  for (int i=0; i < 36; i++) {
    if (mask[i] == '0') {
      tt = 1;
      tt = (tt << (35-i));
      tt = ~tt;
      tt = tt & MASK_ONE;
      bitmask_zero = bitmask_zero & tt;
    }
    else if (mask[i] == '1') {
      tt = 1;
      tt = (tt << (35-i));
      tt = tt & MASK_ONE;
      bitmask_one = bitmask_one | tt;
    }
    else if (mask[i] = 'X') {
      tt = 1;
      tt = (tt << (35-i));
      tt = tt & MASK_ONE;
      bitmask_x = bitmask_x | tt;
    }
    else {
      printf ("xxxxxxxxxxxxx  ERROR xxxxxxxxx\n");
      exit(0);
    }
  }
  print_mask();
}


void exec_mem(long addr, long val) {
  unsigned long xx;
  xx = addr;
  printf("val  = ");
  print_long(val);
  printf("\n");
  printf("addr = ");
  print_long(xx);
  printf("\n");
  printf("mask = %s\n", mask);
  //xx = xx & bitmask_zero;
  xx = xx | bitmask_one;
  printf("filt = ");
  print_long(xx);
  printf("\n");

  printf("memrec ");
  print_long(xx);
  printf(" ");
  print_long(val);
  printf("\n");
  mem_rec(xx, val, 0);
  //mem[addr] = xx;
}

void mem_rec(long addr, long val, int init) {
  int i;
  unsigned long xr;
  unsigned long addr2;
  for(int i = init; i < 36; i++) {
    if (mask[i] == 'X') {
      xr = 0x1ul;
      xr = (xr << 35 - i);
      addr2 = addr | xr;
      mem_rec(addr2, val, i + 1);
      xr = ~xr;
      addr2 = addr & xr;
      mem_rec(addr2, val, i + 1);
      return;
    }
  }
  mem[addr] = val;
  printf("   mem[");
  print_long(addr);
  printf("] = ");
  print_long(val);
  printf("\n");
}

void print_mask() {
  printf("bitmask_zero = ");
  print_long(bitmask_zero);
  printf("\n");

  printf("bitmask_one  = ");
  print_long(bitmask_one);
  printf("\n");

  printf("bitmask_x    = ");
  print_long(bitmask_x);
  printf("\n");
}

void print_long(long xx) {
  long tt;
  for(int i=35; i>=0; i--) {
    tt = xx >> i;
    tt &= 0x1ul;
    printf("%ld", tt);
  }
}

void print_long64(long xx) {
  long tt;
  for(int i=63; i>=0; i--) {
    tt = xx >> i;
    tt &= 0x1ul;
    printf("%ld", tt);
  }
}

void print_answer() {
  long total = 0;
  printf("********* COUNT *********\n");
  map<long, long>::iterator it;
  int count = 0;
  for(it = mem.begin(); it != mem.end(); it++) {
    count++;
    printf("mem[");
    print_long(it->first);
    printf("] = %ld\n", it->second);
    total += it->second;
    if (it->second < 0) {
      printf("++++ ERROR ++++\n");
    }
  }
  printf("count = %d\n", count);
  printf("total = %ld\n", total);
}


