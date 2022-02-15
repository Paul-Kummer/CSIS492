CREATE DEFINER=`root`@`%` PROCEDURE `updatePass`(uID int, oldPass varchar(45), newPass varchar(45))
BEGIN
set @valid = verifyPass(uID, oldPass);

if @valid = 1 then
	update USER
	set user_pass = md5(newPass)
	where 
	uID = user_id;
end if;

END