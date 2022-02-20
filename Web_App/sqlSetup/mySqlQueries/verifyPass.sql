CREATE DEFINER=`root`@`%` FUNCTION `verifyPass`(uName varchar(45), uPass varchar(45)) RETURNS varchar(5)
BEGIN
set @scramblePass = md5(uPass);
set @dbPass = 
(
select user_pass from USER
where user_name like concat('%',uName,'%') || user_id = cast(uName as unsigned int)
limit 1
);

if 
@scramblePass <=> @dbPass then #compares null values too
return 'true';

else
return 'false';
end if;

END