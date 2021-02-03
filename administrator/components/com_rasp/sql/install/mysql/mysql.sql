create table `#__rasp_uids`
(
    id  int unsigned auto_increment primary key,
    dat date         not null,
    uid varchar(255) not null,
    unique index `#__rasp_uids_uid_dat_uindex` (uid, dat)
) character set utf8mb4
  collate utf8mb4_general_ci;

create table `#__rasp_points`
(
    id        smallint unsigned not null primary key auto_increment,
    station_1 int               not null,
    station_2 int               not null,
    constraint `#__rasp_points_#__rw_stations_station_1_id_fk`
        foreign key (station_1) references `#__rw_stations` (id)
            on update restrict on delete restrict,
    constraint `#__rasp_points_#__rw_stations_station_2_id_fk`
        foreign key (station_2) references `#__rw_stations` (id)
            on update restrict on delete restrict
) character set utf8mb4
  collate utf8mb4_general_ci comment 'Станции, между которыми ищется расписание';

INSERT INTO `#__rasp_points` (id, station_1, station_2) VALUES (1, 215, 9215);
INSERT INTO `#__rasp_points` (id, station_1, station_2) VALUES (2, 9581, 7557);
INSERT INTO `#__rasp_points` (id, station_1, station_2) VALUES (3, 5447, 222);
INSERT INTO `#__rasp_points` (id, station_1, station_2) VALUES (4, 222, 4207);
INSERT INTO `#__rasp_points` (id, station_1, station_2) VALUES (5, 3007, 7501);
INSERT INTO `#__rasp_points` (id, station_1, station_2) VALUES (6, 7501, 10740);
INSERT INTO `#__rasp_points` (id, station_1, station_2) VALUES (7, 215, 222);
