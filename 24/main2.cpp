#include <cstdlib>
#include <cstdio>
#include <cstring>

enum dir_t {
  DIR_NONE=0,
  DIR_E,
  DIR_SE,
  DIR_SW,
  DIR_W,
  DIR_NW,
  DIR_NE
};

enum state_t {
  STATE_INIT=0,
  STATE_S,
  STATE_N
};

const int TILES_WIDTH = 2048;
const int TILES_HEIGHT = 2048;

int main(int argc, char **argv);
void copy_tiles(unsigned char *tiles2, unsigned char *tiles);
void iterate_tiles(unsigned char *tiles2, unsigned char *tiles);
int count_adjacent(unsigned char *tiles, int x, int y, int type);
int count_tiles(unsigned char *tiles, int type);
void update_coord(int *x, int *y, dir_t dir);
void print_dir(dir_t dir);
void init_tiles(unsigned char *tiles);
void flip_tile(unsigned char *tiles, int x, int y);
unsigned char get_tile(unsigned char *tiles, int x, int y);
void set_tile(unsigned char *tiles, int x, int y, unsigned char n);


int main(int argc, char **argv) {
  FILE *ff;
  ff = fopen("input", "r");
  int c;
  unsigned char *tiles = 0, *tiles2 = 0;
  int x, y;
  state_t state;

  tiles = (unsigned char *) malloc(TILES_WIDTH * TILES_HEIGHT);
  init_tiles(tiles);

  // initial position
  x = 200, y = 200;
  state = STATE_INIT;
  for(;;) {

    c = fgetc(ff);
    if(feof(ff)) {
      break;
    }
    //printf("ch = %c\n", c);
    printf("%c", c);

    switch(state) {
      case STATE_INIT:
      switch(c) {
        case 'e':
          update_coord(&x, &y, DIR_E);
          break;
        case 's':
          state = STATE_S;
          break;
        case 'w':
          update_coord(&x, &y, DIR_W);
          break;
        case 'n':
          state = STATE_N;
          break;
        case '\n':
          flip_tile(tiles, x, y);
          x = 200; y = 200;
          state = STATE_INIT;
        break;
        default:
          printf("ERROR\n");
	  exit(0);
      }
      break;
      case STATE_S:
      switch(c) {
        case 'e':
          update_coord(&x, &y, DIR_SE);
	  state = STATE_INIT;
          break;
        case 'w':
          update_coord(&x, &y, DIR_SW);
	  state = STATE_INIT;
          break;
        default:
          printf("ERROR\n");
	  exit(0);
      }
      break;
      case STATE_N:
      switch(c) {
        case 'e':
          update_coord(&x, &y, DIR_NE);
	  state = STATE_INIT;
          break;
        case 'w':
          update_coord(&x, &y, DIR_NW);
	  state = STATE_INIT;
          break;
        break;
        default:
          printf("ERROR\n");
	  exit(0);
      }
      break;
      default:
        printf("ERROR\n");
	exit(0);
    }


  }

  fclose(ff);

  int count = 0;
  count = count_tiles(tiles, 1);

  printf("%d black tiles remain\n", count);

  tiles2 = (unsigned char *) malloc(TILES_WIDTH * TILES_HEIGHT);
  copy_tiles(tiles2, tiles);
  for(int i=0; i<100; i++) {
    printf("iteration %d..", i+1);
    iterate_tiles(tiles2, tiles);
    copy_tiles(tiles, tiles2);
    printf(" done\n");
  }

  count = count_tiles(tiles, 1);
  printf("after 100 iterations %d black tiles remain\n", count);

  free(tiles);
  free(tiles2);
  tiles = 0;
  tiles2 = 0;

  return 0;
}

void copy_tiles(unsigned char *tiles2, unsigned char *tiles) {
  int n;
  for(int y=0;y<TILES_HEIGHT;y++) {
    for (int x=0;x<TILES_WIDTH;x++) {
      n = get_tile(tiles, x, y);
      set_tile(tiles2, x, y, n);
    }
  }
}

void iterate_tiles(unsigned char *tileDest, unsigned char *tileSource) {
  int n, xx;
  // leave some space at the edges
  for(int y=2;y<TILES_HEIGHT-2;y++) {
    for (int x=2;x<TILES_WIDTH-2;x++) {
      n = get_tile(tileSource, x, y);
      // black
      if(n == 1) {
        xx = count_adjacent(tileSource, x, y, 1);
	if (xx == 0 || xx > 2) {
	  //flip_tile(tileDest, x, y);
	  set_tile(tileDest, x, y, 0);
	}
      }
      // white
      else {
        xx = count_adjacent(tileSource, x, y, 1);
	if (xx == 2) {
	  //flip_tile(tileDest, x, y);
	  set_tile(tileDest, x, y, 1);
	}
      }
    }
  }
}

int count_adjacent(unsigned char *tiles, int x, int y, int type) {
  dir_t dir[20];
  dir[0] = DIR_E;
  dir[1] = DIR_SE;
  dir[2] = DIR_SW;
  dir[3] = DIR_W;
  dir[4] = DIR_NW;
  dir[5] = DIR_NE;
  int count = 0;
  int xx, yy;
  for(int i=0; i < 6; i++) {
    xx = x;
    yy = y;
    update_coord(&xx, &yy, dir[i]);
    if (get_tile(tiles,xx,yy) == type) {
      count++;
    }
  }
  return count;
}

int count_tiles(unsigned char *tiles, int type) {
  int count = 0;
  int n;
  for(int y=0;y<TILES_HEIGHT;y++) {
    for (int x=0;x<TILES_WIDTH;x++) {
      n = get_tile(tiles, x, y);
      if (n == type) {
        count++;
      }
    }
  }
  return count;
}

void update_coord(int *x, int *y, dir_t dir) {
  // hexes are stored this way
  //  N
  // W E
  //  S
  //
  // [0]     1 2 3 4
  // [1]    1 2 3 4
  // [2]     1 2 3 4
  // [3]    1 2 3 4
 
  int tx = *x, ty = *y;

  //printf("update_coord x = %d y = %d dir = ", tx, ty);
  //print_dir(dir);
  //printf("\n");
  
  // y is even
  if(ty % 2 == 0) {
    switch(dir) {
    case DIR_E:
      tx++;
      break;
    case DIR_SE:
      tx++;
      ty++;
      break;
    case DIR_SW:
      ty++;
      break;
    case DIR_W:
      tx--;
      break;
    case DIR_NW:
      ty--;
      break;
    case DIR_NE:
      tx++;
      ty--;
      break;
    default:
      printf("ERROR\n");
      exit(0);
    }
  }
  // y is odd
  else {
    switch(dir) {
    case DIR_E:
      tx++;
      break;
    case DIR_SE:
      ty++;
      break;
    case DIR_SW:
      tx--;
      ty++;
      break;
    case DIR_W:
      tx--;
      break;
    case DIR_NW:
      tx--;
      ty--;
      break;
    case DIR_NE:
      ty--;
      break;
    default:
      printf("ERROR\n");
      exit(0);
    }
  }

  //printf("new coords x = %d y = %d\n", tx, ty);
  if (tx < 0 || ty < 0 || tx >= TILES_WIDTH || tx >= TILES_HEIGHT) {
    printf("ERROR out of bounds error in update_coord\n");
    exit(0);
  }

  *x = tx;
  *y = ty;
}

void print_dir(dir_t dir) {
  switch(dir) {
    case DIR_NONE:
      printf("NONE");
      break;
    case DIR_E:
      printf("E");
      break;
    case DIR_SE:
      printf("SE");
      break;
    case DIR_SW:
      printf("SW");
      break;
    case DIR_W:
      printf("W");
      break;
    case DIR_NW:
      printf("NW");
      break;
    case DIR_NE:
      printf("NE");
      break;
    default:
      printf("ERROR\n");
      exit(0);
  };
}

// 0 means white
// 1 means black
void init_tiles(unsigned char *tiles) {
  for(int y=0;y<TILES_HEIGHT;y++) {
    for (int x=0;x<TILES_WIDTH;x++) {
      set_tile(tiles, x, y, 0);
    }
  }

};

void flip_tile(unsigned char *tiles, int x, int y) {
  unsigned char n;
  n = get_tile(tiles, x, y);
  if (n == 0) {
    set_tile(tiles, x, y, 1);
  }
  else {
    set_tile(tiles, x, y, 0);
  }
}

unsigned char get_tile(unsigned char *tiles, int x, int y) {
  if (x < 0 || y < 0 || x >= TILES_WIDTH || x >= TILES_HEIGHT) {
    printf("ERROR in get_tile\n");
    exit(0);
  }
  return tiles[y * TILES_WIDTH + x];
}

void set_tile(unsigned char *tiles, int x, int y, unsigned char n) {
  if (x < 0 || y < 0 || x >= TILES_WIDTH || x >= TILES_HEIGHT) {
    printf("ERROR in set_tile\n");
    exit(0);
  }
  tiles[y * TILES_WIDTH + x] = n;
}




