#include <cstdlib>
#include <cstdio>
#include <cstring>



int verify(int min, int max, char ch, char *ss);

int main(int argc, char **argv) {
  FILE *ff;
  int min, max;
  char ch;
  char ss[2048];
  int valid_count = 0;
  ff = fopen("input", "r");
  for(;;) {
    if (feof(ff)) {
      break;
    }
    fscanf(ff, "%d-%d %c: %s\n", &min, &max, &ch, ss);
    //printf("%d-%d %c= %s\n", min, max, ch, ss);
    if (verify(min, max, ch, ss) == 1) {
      valid_count++;
    }
  }
  printf("valid count = %d\n", valid_count);

  fclose(ff);
}

int verify(int min, int max, char ch, char *ss) {
  int count = 0;
  int n = strlen(ss);
  printf("verify %d-%d %c: %s", min, max, ch, ss);
  for(int i = 0; i < n; i++) {
    if(ss[i] == ch) {
      count++;
    }
  }
  if (count >= min && count <= max) {
    printf(" ok\n");
    return 1;
  }
  else {
    printf(" INVALID\n");
    return 0;
  }
}


