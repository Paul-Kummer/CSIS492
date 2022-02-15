CREATE DEFINER=`root`@`%` PROCEDURE `sp_makeUser`(uName varchar(45), uPass varchar(45))
BEGIN
insert into USER 
(user_name, user_pass)
Values
(
uName,
md5(uPass)
);
END