create table accounts
(
    id            serial
        constraint accounts_pk
            primary key,
    first_name    varchar(32)             not null,
    last_name     varchar(32)             not null,
    phone         varchar(16)             not null,
    email         varchar(64)             not null,
    image         bytea,
    image_type    varchar(32),
    password      varchar(255)            not null,
    password_salt bytea             not null,
    business_name varchar(32),
    created_at    timestamp default now() not null,
    updated_at    timestamp default now() not null
);

create unique index accounts_email_uindex
    on accounts (email);

create unique index accounts_phone_uindex
    on accounts (phone);

/

create table announcements
(
    id               serial
        constraint announcements_pk
            primary key,
    account_id       int                        not null
        constraint fk_announcements_account_id
            references accounts
            on update cascade on delete cascade,
    title            varchar(128)               not null,
    type             varchar(32) default 'land' not null,
    price            int                        not null,
    surface          int                        not null,
    address          varchar(255)               not null,
    lat              real                       not null,
    lon              real                       not null,
    transaction_type varchar(64)                not null,
    description      varchar(4000),
    created_at       timestamp   default now()  not null,
    updated_at       timestamp   default now()  not null
);


/

create table saves
(
    account_id      int not null,
    announcement_id int not null,
    constraint saves_pk
        primary key (account_id, announcement_id)
);


/

create table images
(
    id              serial
        constraint images_pk
            primary key,
    announcement_id int          not null
        constraint images_announcements_id_fk
            references announcements
            on update cascade on delete cascade,
    name            varchar(255) not null,
    type            varchar(32)  not null,
    image           bytea        not null
);

/

create table buildings
(
    announcement_id int
        constraint buildings_pk
            primary key
        constraint buildings_announcements_id_fk
            references announcements
            on update cascade on delete cascade,
    floor           int,
    bathrooms       int default 1,
    parking_lots    int default 1,
    build_in        int,
    ap_type         varchar(32),
    rooms           int,
    basement        boolean
);

/

create function set_timestamp_function()
    RETURNS TRIGGER AS
$$
begin
    new.updated_at = now();
    return new;
end ;
$$ LANGUAGE plpgsql;


create trigger set_timestamp_trigger
    before update
    on accounts
    for each row
execute procedure set_timestamp_function();

create trigger set_timestamp_trigger
    before update
    on announcements
    for each row
execute procedure set_timestamp_function();

/