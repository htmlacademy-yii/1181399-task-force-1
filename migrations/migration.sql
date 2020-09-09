create database if not exists task_force;
use task_force;

# set foreign_key_checks = 0;

create table if not exists cities (
    id bigint not null auto_increment,
    map_h varchar(15) not null,
    map_w varchar(15) not null,
    name varchar(30) not null,

    primary key (id)
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists users (
    id bigint not null auto_increment,
    name varchar(255) not null,
    birthday date not null,
    description longtext,
    email varchar(255) not null,
    password varchar(255) not null,
    phone varchar(20),
    skype varchar(50),
    telegram varchar(50),
    avatar_url text,
    last_visit datetime default current_timestamp,
    city_id bigint not null,
    address text,
    notification_message bool default true,
    notification_actions bool default true,
    notification_feedback bool default true,
    public_contacts bool default false,
    public_profile bool default true,
    created_at datetime default current_timestamp,
    updated_at datetime default current_timestamp on update current_timestamp,

    primary key (id),
    foreign key (city_id) references cities (id) on update cascade on delete cascade,
    unique (id)
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists bookmarks (
    id bigint not null auto_increment,
    user_id bigint not null,
    bookmark_user_id bigint not null,

    primary key (id),
    foreign key (user_id) references users (id) on update cascade on delete cascade,
    foreign key (bookmark_user_id) references users (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists categories (
    id bigint not null auto_increment,
    icon text,
    name varchar(255) not null,

    primary key (id)
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists category_user (
    id bigint not null auto_increment,
    user_id bigint not null,
    category_id bigint not null,

    primary key (id),
    foreign key (user_id) references users (id) on update cascade on delete cascade,
    foreign key (category_id) references categories (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists tasks (
    id bigint not null auto_increment,
    author_id bigint not null,
    executor_id bigint,
    category_id bigint not null,
    city_id bigint not null,
    budget int unsigned,
    description longtext,
    title varchar(255) not null,
    created_at datetime default current_timestamp,
    updated_at datetime default current_timestamp on update current_timestamp,
    until date not null,
    status enum('new', 'cancelled', 'wip', 'done', 'failed') default 'new',
    address text,
    address_comment text,
    map_w varchar(15),
    map_h varchar(15),

    primary key (id),
    foreign key (author_id) references users (id) on update cascade on delete cascade,
    foreign key (executor_id) references users (id) on update cascade on delete set null,
    foreign key (city_id) references cities (id) on update cascade on delete cascade,
    foreign key (category_id) references categories (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists feedback (
    id bigint not null auto_increment,
    task_id bigint not null,
    author_id bigint not null,
    user_id bigint not null,
    status enum('success', 'failed'),
    comment text,
    rating tinyint,
    created_at datetime default current_timestamp,
    updated_at datetime default current_timestamp on update current_timestamp,

    primary key (id),
    foreign key (author_id) references users (id) on update cascade on delete cascade,
    foreign key (user_id) references users (id) on update cascade on delete cascade,
    foreign key (task_id) references tasks (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists applications (
    id bigint not null auto_increment,
    user_id bigint not null,
    task_id bigint not null,
    budget int unsigned not null,
    comment text,
    created_at datetime default current_timestamp,
    updated_at datetime default current_timestamp on update current_timestamp,
    status enum('new', 'accepted', 'declined') default 'new',

    primary key (id),
    foreign key (user_id) references users (id) on update cascade on delete cascade,
    foreign key (task_id) references tasks (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists messages (
    id bigint not null auto_increment,
    author_id bigint not null,
    recipient_id bigint not null,
    task_id bigint not null,
    content text not null,
    is_read bool default false,
    created_at datetime default current_timestamp,
    updated_at datetime default current_timestamp on update current_timestamp,

    primary key (id),
    foreign key (author_id) references users (id) on update cascade on delete cascade,
    foreign key (recipient_id) references users (id) on update cascade on delete cascade,
    foreign key (task_id) references tasks (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists attachments (
    id bigint not null auto_increment,
    url text not null,
    name varchar(255) not null,
    created_at datetime default current_timestamp,
    updated_at datetime default current_timestamp on update current_timestamp,

    primary key (id)
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists attachment_user (
    id bigint not null auto_increment,
    user_id bigint not null,
    attachment_id bigint not null,

    primary key (id),
    foreign key (user_id) references users (id) on update cascade on delete cascade,
    foreign key (attachment_id) references attachments (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists attachment_task (
    id bigint not null auto_increment,
    task_id bigint not null,
    attachment_id bigint not null,

    primary key (id),
    foreign key (attachment_id) references attachments (id) on update cascade on delete cascade,
    foreign key (task_id) references tasks (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

create table if not exists feed (
    id bigint not null auto_increment,
    user_id bigint not null,
    type enum ('application', 'chat', 'reject', 'start', 'end', 'feedback') not null,
    task_id bigint not null,
    description text not null,

    primary key (id),
    foreign key (user_id) references users (id) on update cascade on delete cascade,
    foreign key (task_id) references tasks (id) on update cascade on delete cascade
) character set utf8mb4 collate utf8mb4_general_ci;

# set foreign_key_checks = 1;
