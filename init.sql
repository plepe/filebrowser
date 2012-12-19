create table directory (
  directory_id  integer not null,
  archive_id    text    not null,
  path          text    not null,
  primary key(directory_id)
);

create table file (
  directory_id  integer not null,
  filename      text    not null,
  primary key(directory_id, filename)
);
