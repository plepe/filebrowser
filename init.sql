create table directory (
  directory_id  integer not null,
  archive_id    text    not null,
  path          text    not null,
  primary key(directory_id)
);

create table directory_link (
  directory_id  integer not null,
  filename      text    not null,
  sub_directory integer null,
  primary key(directory_id, filename)
);

create table file (
  directory_id  integer not null,
  filename      text    not null,
  primary key(directory_id, filename)
);
