CREATE DEFINER=`root`@`%` PROCEDURE `addSession`(uID int, rtID int, sessInfo varchar(8000))
BEGIN
insert into SESSION
(rt_id, usr_id, sess_date, sess_info)
values
(
rtID,
uID,
CURDATE(),
sessInfo
);
END