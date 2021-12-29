<?php

require_once ("Authority.php");
require_once ("DataBaseAdapter.php");
require_once ("IAdministrator.php");
require_once ("IFactory.php");
require_once ("Iperson.php");
require_once ("MedicalOfficer.php");
require_once ("PDOSingleton.php");
require_once("Person.php");
require_once ("PersonProxy.php");
require_once ("User.php");
require_once("UserFactory.php");
require_once ("UserProxy.php");
require_once ("UserProxyFactory.php");
require_once ("VaccineRecord.php");
require_once ("MailWrapper.php");

require_once("States/AccountState.php");
require_once("States/AccountStateFactory.php");
require_once("States/ActiveUser.php");
require_once("States/InactiveUser.php");
require_once("States/PreUser.php");

require_once("States/UserState.php");
require_once("States/Deceased.php");
require_once("States/Healthy.php");
require_once("States/Infected.php");
require_once("States/Quarantined.php");
require_once("States/UserStateFactory.php");

require_once("States/VaccinationState.php");
require_once("States/VaccinationStateFactory.php");
require_once("States/PartiallyVaccinated.php");
require_once("States/FullyVaccinated.php");
require_once("States/NotVaccinated.php");

require_once("States/NotificationState.php");
require_once("States/NotificationStateFactory.php");
require_once("States/Read.php");
require_once("States/Unread.php");

require_once("States/State.php");
require_once("States/StateFactory.php");
require_once("States/IUser.php");
?>

