CREATE DEFINER=`root`@`%` PROCEDURE `getMaxClimb`(uID int)
BEGIN
select 
r.rt_name as Name, 
r.rt_diff as Difficulty,
s.sess_date as Date
from SESSION s
left join ROUTE r on r.rt_id = s.rt_id
where uID = s.usr_id
order by r.rt_diff desc
limit 1;
END