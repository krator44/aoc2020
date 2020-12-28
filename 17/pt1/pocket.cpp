#include <cstdlib>
#include <cstdio>
#include <cstring>

#include "pocket.h"

// Grid
Grid::Grid() {
  max_x = 40;
  max_y = 40;
  max_z = 40;
  data = new unsigned char[max_x * max_y * max_z];
  reset_grid();
}

Grid::Grid(int max_xx, int max_yy, int max_zz) {
  max_x = max_xx;
  max_y = max_yy;
  max_z = max_zz;
  data = new unsigned char[max_x * max_y * max_z];
}

Grid::Grid(const Grid &tt) {
  max_x = tt.max_x;
  max_y = tt.max_y;
  max_z = tt.max_z;
  int size = max_x * max_y * max_z;
  data=new unsigned char[size];
  memcpy(data, tt.data, size);
}

Grid& Grid::operator= (const Grid &tt){
  if(data != 0) {
    delete[] data;
  }
  max_x = tt.max_x;
  max_y = tt.max_y;
  max_z = tt.max_z;
  int size = max_x * max_y * max_z;
  data=new unsigned char[size];
  memcpy(data, tt.data, size);
  return *this;
}

Grid::~Grid() {
  if(data != 0) {
    delete[] data;
    data = 0;
  }
}

void Grid::read_input(const char *fn,
  int init_x, int init_y, int init_z) {
  FILE *ff;
  ff = fopen(fn, "r");
  if (ff == 0) {
    printf("error: input file not found\n");
    exit(0);
  }

  char ss[2048];
  int count = 0;
  int n;
  int x,y,z;
  x = init_x;
  y = init_y;
  z = init_z;
  for(;;) {
    if(fgets(ss, 2048, ff) == 0) {
      break;
    }
    n = strlen(ss);
    // cut newline
    ss[n-1]=0;
    n--;

    for(int i=0;i<n;i++) {
      int value = decode_char(ss[i]);
      set_cube(x+i, y+count, z, value);
    }
    count++;
  }
  if(count == 0) {
    printf("error: no numbers in input file\n");
    exit(0);
  }

  //print_slice(20);

  fclose(ff);
}

void Grid::set_cube(int x, int y, int z, unsigned char n) {
  if(!check_bounds(x,y,z)) {
    printf("error: coordinate out of bounds in grid.set_cube()\n");
    print_coords(x,y,z);
    exit(0);
  }
  data[max_x*max_y*z+max_x*y+x] = n;
}

unsigned char Grid::get_cube(int x, int y, int z) const {
  return data[max_x*max_y*z+max_x*y+x];
}

void Grid::reset_grid() {
  for(int z=0;z<max_z;z++) {
    for(int y=0;y<max_y;y++) {
      for(int x=0;x<max_x;x++) {
        set_cube(x, y, z, 0);
      }
    }
  }
}


int Grid::adjacent(int x, int y, int z, unsigned char state) const {
  int count = 0;
  //printf("x,y,z %d %d %d\n", x, y, z);
  for(int i=-1;i<=1;i++) {
    for(int j=-1;j<=1;j++) {
      for(int k=-1;k<=1;k++) {
        //printf("i,j,k %d %d %d\n", i, j, k);
        if(i == 0 && j == 0 && k == 0) {
          continue;
        }
        if(!check_bounds(x+i, y+j, z+k)) {
          //printf("out of bounds!\n");
          continue;
        }
        //printf("get_cube %d %d %d\n", x+i, y+j, z+k);
        if (get_cube(x+i, y+j, z+k) == state) {
          //printf("neighbour!\n");
          //printf("[%d,%d,%d] has neighbour [%d,%d,%d]!\n",
          //  x,y,z,x+i,y+j,z+k
          //);
          count++;
        }
      }
    }
  }
  return count;
}

int Grid::count_cubes(unsigned char state) const {
  int count = 0;
  for(int z=0;z<max_z;z++) {
    for(int y=0;y<max_y;y++) {
      for(int x=0;x<max_x;x++) {
        if(get_cube(x, y, z) == state) {
          count++;
        }
      }
    }
  }
  return count;
}

bool Grid::check_bounds(int x, int y, int z) const {
  if(x < 0 || x >= max_x) {
    return false;
  }
  if(y < 0 || y >= max_y) {
    return false;
  }
  if(z < 0 || z >= max_z) {
    return false;
  }
  return true;
}

void Grid::print_coords(int x, int y, int z) {
  printf("x = %d y = %d z = %d\n", x, y, z);
}

void Grid::print_cube(unsigned char n) {
  if(n==0) {
    printf(".");
  }
  else {
    printf("#");
  }
}

unsigned char Grid::decode_char(char ch) {
  if (ch == '#') {
    return 1;
  }
  else if (ch == '.') {
    return 0;
  }
  else {
    printf("input error: unexpected char %c\n", ch);
    exit(0);
  }
}

void Grid::print_slice(int z) const {
  for(int y=0; y<max_y; y++) {
    for(int x=0; x<max_x; x++) {
      unsigned char n = get_cube(x, y, z);
      print_cube(n);
    }
    printf("\n");
  }
}

void Grid::print_view() const {
  slice_t slice = determine_view();
  printf("x [%d,%d] y [%d,%d] z [%d,%d]\n",
    slice.min_x, slice.max_x,
    slice.min_y, slice.max_y,
    slice.min_z, slice.max_z);
  for(int z=slice.min_z; z<=slice.max_z; z++) {
    printf("z=%d\n", z);
    for(int y=slice.min_y; y<=slice.max_y; y++) {
      for(int x=slice.min_x; x<=slice.max_x; x++) {
        unsigned char n = get_cube(x, y, z);
        print_cube(n);
      }
      printf("\n");
    }
    printf("\n");
  }
}

slice_t Grid::determine_view() const {
  slice_t tt;
  tt.min_x = max_x + 1;
  tt.min_y = max_y + 1;
  tt.min_z = max_z + 1;
  tt.max_x = -1;
  tt.max_y = -1;
  tt.max_z = -1;

  bool flag = false;
  for(int z=0;z<max_z;z++) {
    for(int y=0;y<max_y;y++) {
      for(int x=0;x<max_x;x++) {
        unsigned char n = get_cube(x, y, z);
        if(n == 1) {
          if (tt.min_x > x) {
            tt.min_x = x;
          }
          if (tt.min_y > y) {
            tt.min_y = y;
          }
          if (tt.min_z > z) {
            tt.min_z = z;
          }
          if (tt.max_x < x) {
            tt.max_x = x;
          }
          if (tt.max_y < y) {
            tt.max_y = y;
          }
          if (tt.max_z < z) {
            tt.max_z = z;
          }
          flag = true;
        }
      }
    }
  }
  // no cubes are active
  if (flag == false) {
    tt.min_x = max_x / 2;
    tt.max_x = max_x / 2;
    tt.min_y = max_y / 2;
    tt.max_y = max_y / 2;
    tt.min_z = max_z / 2;
    tt.max_z = max_z / 2;
  }
  return tt;
}



// Pocket
Pocket::Pocket() : grid() {
}

Pocket::Pocket(int max_xx, int max_yy, int max_zz) 
  : grid(max_xx, max_yy, max_zz) {
  max_x = max_xx;
  max_y = max_yy;
  max_z = max_zz;
}

void Pocket::iterate() {
  Grid spare(grid);
  for(int z=0;z<max_z;z++) {
    for(int y=0;y<max_y;y++) {
      for(int x=0;x<max_x;x++) {
        unsigned char state = grid.get_cube(x, y, z);
        int neighbours = grid.adjacent(x, y, z, 1);
  //      printf("cube [%d,%d,%d] s=%d n=%d\n", x, y, z,
  //        state, neighbours);
        if (state == 0) {
          if (neighbours == 3) {
            //printf("cube [%d,%d,%d] s=%d n=%d flips\n", x, y, z,
            //    state, neighbours);
            spare.set_cube(x, y, z, 1);
          }
        }
        else if (state == 1) {
          if (!(neighbours == 2 || neighbours == 3)) {
            //printf("cube [%d,%d,%d] s=%d n=%d flips\n", x, y, z,
            //    state, neighbours);
            spare.set_cube(x, y, z, 0);
          }
        }
        else {
          printf("error: state = %d in iterate\n", state);
          exit(0);
        }
        //printf("neighbours %d\n", neighbours);
      }
    }
  }
  grid = spare;
  /*
     unsigned char *spare;
     int size = max_x*max_y*max_z;
     spare = new unsigned char[size];
     for(int y

     memcpy(grid, spare, size);
     free(spare);
     spare = 0;
     */
}




