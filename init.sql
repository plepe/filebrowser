create table directory (
  directory_id  integer not null,
  archive_id    text    not null,
  path          text    not null,
  primary key(directory_id)
);

create table directory_content (
  directory_id  integer not null,
  name      text    not null,
  sub_directory integer null, -- directory_id of sub directory, null indicates file
  primary key(directory_id, name)
);

create virtual table search_index using fts4(directory_id, name);
