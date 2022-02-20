CREATE DEFINER=`root`@`%` PROCEDURE `getLastSessionDate`(uID int)
BEGIN
select
sess_date 'Date'
from SESSION
where uID = usr_id
order by sess_date desc
limit 1;
END