CREATE DEFINER=`root`@`%` PROCEDURE `validPassword`(uName varchar(45), uPass varchar(45))
BEGIN
select verifyPass(uName, uPass) as isValid;
END