create table directory (
  directory_id  integer not null,
  archive_id    text    not null,
  path          text    not null,
  primary key(directory_id)
);

create table directory_link (
  directory_id  integer not null,
  name      text    not null,
  sub_directory integer null,
  primary key(directory_id, name)
);

create table file (
  directory_id  integer not null,
  name      text    not null,
  primary key(directory_id, name)
);
