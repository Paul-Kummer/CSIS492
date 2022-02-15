CREATE DEFINER=`root`@`%` PROCEDURE `getUserIdFromName`(uName varchar(45))
BEGIN
select user_id
from USER
where user_name like concat('%',uName,'%') || user_id = uName
limit 1;
END