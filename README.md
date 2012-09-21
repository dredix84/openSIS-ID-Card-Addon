openSIS-ID-Card-Addon
=====================

This is a addon for openSIS which will allow the creation of ID cards based on student data
See the URL below for more details.
http://www.dredix.net/downloads/viewdownload/4-opensis/2-opensis-id-card-generator


Install Instructions
====================

1. Copy the for_idcardexpt.php file to the root of you openSIS directory.
2. Copy the StudIDCard.php file to the modules/Students directory.
3. Modify the Menu.php file found inside the modules/Students directory to add the following lines to the $menu['Students']['admin'] array.

						3=>'Student Cards',
						'Students/StudIDCard.php'=>'Student ID Cards',
						'Students/StudIDCard.php?setup=1'=>'Student ID Card Setup'
						
Note: the last items in the array should not have a comma(,).
4. Login into openSIS to give yourself permission to use this addon by going to Users => Setup => Profile then click Administrator. A list of permissions should appear. Look for "Student ID Card Setup" and "Student ID Cards" then ckeck the associated checkboxes for both (make sure to save).
5. Log out and log back in and you should now find the ID Card menu items under Student => Student Cards.

Have fun.



Example of a properly modified $menu['Students']['admin'] array in the Menu.php file.

$menu['Students']['admin'] = array(
						'Students/Student.php'=>'Student Info',
						'Students/Student.php&include=General_Info&student_id=new'=>'Add a Student',
						'Students/AssignOtherInfo.php'=>'Group Assign Student Info',
						'Students/AddUsers.php'=>'Associate Parents with Students',
						1=>'Reports',
						'Students/AdvancedReport.php'=>'Advanced Report',
						'Students/AddDrop.php'=>'Add / Drop Report',
						'Students/Letters.php'=>'Print Letters',
						'Students/MailingLabels.php'=>'Print Mailing Labels',
						'Students/StudentLabels.php'=>'Print Student Labels',
						'Students/PrintStudentInfo.php'=>'Print Student Info',
                        'Students/GoalReport.php'=>'Print Goals & Progresses',
						2=>'Setup',
						'Students/StudentFields.php'=>'Student Fields',
						#'Students/AddressFields.php'=>'Address Fields',
						#'Students/PeopleFields.php'=>'Contact Fields',
						'Students/EnrollmentCodes.php'=>'Enrollment Codes',
						'Students/Upload.php'=>'Upload Student Photo',
						'Students/Upload.php?modfunc=edit'=>'Update Student Photo',
						3=>'Student Cards',
						'Students/StudIDCard.php'=>'Student ID Cards',
						'Students/StudIDCard.php?setup=1'=>'Student ID Card Setup'
						
					);