#include <cstdlib>
#include <cstdio>
#include <cstring>


int main(int argc, char **argv);
long find_timestamp(long timestamp, long *numbers, int count);
void swap(long *x, long *y);

int main(int argc, char **argv) {
  FILE *ff;
  ff = fopen("input", "r");
  char s[2048];

  long timestamp;
  long numbers[64];

  fscanf(ff, "%ld\n", &timestamp);

  int count = 0;
  for(int i=0;i<64;i++) {
    int result;
    result = fscanf(ff, "%ld,", &numbers[i]);
    if(feof(ff)) {
      break;
    }
    if (result != 1) {
      result = fscanf(ff, "%ld\n", &numbers[i]);
      if(feof(ff)) {
        break;
      }
      if (result == 1) {
        count++;
        break;
      }
      else {
        fscanf(ff, "x,");
        if(feof(ff)) {
          break;
        }
        numbers[i] = -1;
      }
    }
    count++;
  }

  printf("timestamp %ld\n", timestamp);
  
  for(int i=0;i<count;i++) {
    printf("number[%d] = %ld\n", i, numbers[i]);
  }

  fclose(ff);


  printf("working..\n");
  long depart = find_timestamp(timestamp, numbers, count);

  printf("earliest timestamp is %ld\n", depart);

  return 0;
}


long find_timestamp(long timestamp, long *numbers, int count) {
  long xx = timestamp;
  long remainder = xx % numbers[0];
  long remainder2;
  bool flag;
  int cycle = 0;
  xx += (numbers[0] - remainder);

  long busses[64], remainders[64];
  
  int j=0;
  for(int i=0; i<count; i++) {
    if(numbers[i] == -1) {
      continue;
    }
    else {
      busses[j] = numbers[i];
      remainders[j] = i;
      j++;
    }
  }
  int table_count = j;

  // sort the table
  for(;;) {
    flag = true;
    for (int j = 0; j < (table_count-1); j++) {
      if(busses[j] < busses[j+1]) {
        swap(&busses[j], &busses[j+1]);
        swap(&remainders[j], &remainders[j+1]);
        flag = false;
      }
    }
    if(flag == true) {
      break;
    }
  }

  for(j=0;j<table_count;j++) {
    printf("bus %ld remainder %ld\n", busses[j], remainders[j]);
  }

  printf("starting timestamp %ld\n", xx);
  for(;;) {
    flag = true;
    for(int i=0; i < table_count; i++) {
      remainder = (xx+remainders[i]) % busses[i];
      if(remainder != 0) {
        remainder2 = busses[i] - remainder;
        xx += remainder2;
        flag = false;
        break;
      }
    }

    if (flag == true) {
      return xx;
    }
    
    cycle++;
    if(cycle > 1000000000) {
      printf("current timestamp %ld\n", xx);
      cycle = 0;
    }
  }
}

void swap(long *x, long *y) {
  long tt;
  tt = *x;
  *x = *y;
  *y = tt;
}


