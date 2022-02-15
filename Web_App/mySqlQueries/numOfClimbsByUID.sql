CREATE DEFINER=`root`@`%` PROCEDURE `numOfClimbsByUID`(uID int)
BEGIN
select count(session_id)
from SESSION
where uID = usr_id;
END