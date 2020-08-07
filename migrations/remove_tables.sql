create database if not exists task_force;
use task_force;

set foreign_key_checks = 0;

drop table if exists cities;
drop table if exists users;
drop table if exists feedback;
drop table if exists bookmarks;
drop table if exists categories;
drop table if exists category_user;
drop table if exists tasks;
drop table if exists applications;
drop table if exists messages;
drop table if exists attachments;
drop table if exists attachment_user;
drop table if exists attachment_task;
drop table if exists feed;

set foreign_key_checks = 1;
