CREATE DEFINER=`root`@`%` PROCEDURE `changeRtInfo`(rtToChange int, newInfo varchar(8000))
BEGIN
update ROUTE
set rt_info = newInfo
where rt_id = rtToChange;
END