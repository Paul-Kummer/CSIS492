CREATE PROCEDURE `addRoute` 
(rtName varchar(45), rtDiff int, rtSetter int, rtInfo varchar(8000))
BEGIN
insert into ROUTE
(rt_name, rt_diff, rt_setter_id, rt_date, rt_info)
values
(
rtName,
rtDiff,
rtSetter,
curdate(),
rtInfo
);
END