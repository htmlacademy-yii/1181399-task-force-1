set autocommit=0;begin;insert into categories (`name`, `icon`) values ('Переводы', 'translation');
insert into categories (`name`, `icon`) values ('Уборка', 'clean');
insert into categories (`name`, `icon`) values ('Переезды', 'cargo');
insert into categories (`name`, `icon`) values ('Компьютерная помощь', 'neo');
insert into categories (`name`, `icon`) values ('Ремонт квартирный', 'flat');
insert into categories (`name`, `icon`) values ('Ремонт техники', 'repair');
insert into categories (`name`, `icon`) values ('Красота', 'beauty');
insert into categories (`name`, `icon`) values ('Фото', 'photo');
commit;set autocommit=1;