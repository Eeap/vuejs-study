create table boardTable(
    id int auto_increment,
    `author` varchar(50),
    `content` varchar(100),
    updatetime DATETIME DEFAULT now(),
    createtime DATETIME DEFAULT now(),
    PRIMARY KEY(id)
);

create table calendar(
    id int auto_increment,
    content varchar(50),
    money int,
    year int,
    month int,
    day int,
    PRIMARY KEY(id)
);
insert into boardTable(author,content)
values("수민","테스트");