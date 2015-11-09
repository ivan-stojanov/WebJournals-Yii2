<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_194105_add_additional_register_fields_to_usertable extends Migration
{
	public function up()
	{
		$this->addColumn('user', 'salutation', 'varchar(100)');
		$this->addColumn('user', 'middle_name', 'varchar(100)');
		$this->addColumn('user', 'initials', 'varchar(10)');
		$this->addColumn('user', 'gender', 'varchar(10)');
		/*$this->addColumn('user', 'gender', 'enum(	
													"Male",
													"Female",
													"Other",
												)'
						);*/
		$this->addColumn('user', 'affiliation', 'text');
		$this->addColumn('user', 'signature', 'text');
		$this->addColumn('user', 'orcid_id', 'varchar(100)');
		$this->addColumn('user', 'url', 'varchar(100)');
		$this->addColumn('user', 'phone', 'varchar(30)');
		$this->addColumn('user', 'fax', 'varchar(30)');
		$this->addColumn('user', 'mailing_address', 'text');
		$this->addColumn('user', 'country', 'varchar(30)');
		/*$this->addColumn('user', 'country', 'enum(	
													"Afghanistan", 
													"Aland Islands", 
													"Albania", 
													"Algeria", 
													"American Samoa", 
													"Andorra", 
													"Angola", 
													"Anguilla", 
													"Antarctica", 
													"Antigua", 
													"Argentina", 
													"Armenia", 
													"Aruba", 
													"Australia", 
													"Austria", 
													"Azerbaijan", 
													"Bahamas", 
													"Bahrain", 
													"Bangladesh", 
													"Barbados", 
													"Barbuda", 
													"Belarus", 
													"Belgium", 
													"Belize", 
													"Benin", 
													"Bermuda", 
													"Bhutan", 
													"Bolivia", 
													"Bosnia", 
													"Botswana", 
													"Bouvet Island", 
													"Brazil", 
													"British Indian Ocean Trty.", 
													"Brunei Darussalam", 
													"Bulgaria", 
													"Burkina Faso", 
													"Burundi", 
													"Caicos Islands", 
													"Cambodia", 
													"Cameroon", 
													"Canada", 
													"Cape Verde", 
													"Cayman Islands", 
													"Central African Republic", 
													"Chad", 
													"Chile", 
													"China", 
													"Christmas Island", 
													"Cocos (Keeling) Islands", 
													"Colombia", 
													"Comoros", 
													"Congo", 
													"Congo, Democratic Republic of the", 
													"Cook Islands", 
													"Costa Rica", 
													"Cote d Ivoire", 
													"Croatia", 
													"Cuba", 
													"Cyprus", 
													"Czech Republic", 
													"Denmark", 
													"Djibouti", 
													"Dominica", 
													"Dominican Republic", 
													"Ecuador", 
													"Egypt", 
													"El Salvador", 
													"Equatorial Guinea", 
													"Eritrea", 
													"Estonia", 
													"Ethiopia", 
													"Falkland Islands (Malvinas)", 
													"Faroe Islands", 
													"Fiji", 
													"Finland", 
													"France", 
													"French Guiana", 
													"French Polynesia", 
													"French Southern Territories", 
													"Futuna Islands", 
													"Gabon", 
													"Gambia", 
													"Georgia", 
													"Germany", 
													"Ghana", 
													"Gibraltar", 
													"Greece", 
													"Greenland", 
													"Grenada", 
													"Guadeloupe", 
													"Guam", 
													"Guatemala", 
													"Guernsey", 
													"Guinea", 
													"Guinea-Bissau", 
													"Guyana", 
													"Haiti", 
													"Heard", 
													"Herzegovina", 
													"Holy See", 
													"Honduras", 
													"Hong Kong", 
													"Hungary", 
													"Iceland", 
													"India", 
													"Indonesia", 
													"Iran (Islamic Republic of)", 
													"Iraq", 
													"Ireland", 
													"Isle of Man", 
													"Israel", 
													"Italy", 
													"Jamaica", 
													"Jan Mayen Islands", 
													"Japan", 
													"Jersey", 
													"Jordan", 
													"Kazakhstan", 
													"Kenya", 
													"Kiribati", 
													"Korea", 
													"Korea (Democratic)", 
													"Kuwait", 
													"Kyrgyzstan", 
													"Lao", 
													"Latvia", 
													"Lebanon", 
													"Lesotho", 
													"Liberia", 
													"Libyan Arab Jamahiriya", 
													"Liechtenstein", 
													"Lithuania", 
													"Luxembourg", 
													"Macao", 
													"Macedonia", 
													"Madagascar", 
													"Malawi", 
													"Malaysia", 
													"Maldives", 
													"Mali", 
													"Malta", 
													"Marshall Islands", 
													"Martinique", 
													"Mauritania", 
													"Mauritius", 
													"Mayotte", 
													"McDonald Islands", 
													"Mexico", 
													"Micronesia", 
													"Miquelon",
													"Moldova", 
													"Monaco", 
													"Mongolia", 
													"Montenegro", 
													"Montserrat", 
													"Morocco", 
													"Mozambique", 
													"Myanmar", 
													"Namibia", 
													"Nauru", 
													"Nepal", 
													"Netherlands", 
													"Netherlands Antilles", 
													"Nevis", 
													"New Caledonia", 
													"New Zealand", 
													"Nicaragua", 
													"Niger", 
													"Nigeria", 
													"Niue", 
													"Norfolk Island", 
													"Northern Mariana Islands", 
													"Norway", 
													"Oman", 
													"Pakistan", 
													"Palau", 
													"Palestinian Territory, Occupied", 
													"Panama", 
													"Papua New Guinea", 
													"Paraguay", 
													"Peru", 
													"Philippines", 
													"Pitcairn", 
													"Poland", 
													"Portugal", 
													"Principe", 
													"Puerto Rico", 
													"Qatar", 
													"Reunion", 
													"Romania", 
													"Russian Federation", 
													"Rwanda", 
													"Saint Barthelemy", 
													"Saint Helena", 
													"Saint Kitts", "Saint Lucia", 
													"Saint Martin (French part)", 
													"Saint Pierre", "Saint Vincent", 
													"Samoa", 
													"San Marino", 
													"Sao Tome", 
													"Saudi Arabia", 
													"Senegal", 
													"Serbia", 
													"Seychelles", 
													"Sierra Leone", 
													"Singapore", 
													"Slovakia", 
													"Slovenia", 
													"Solomon Islands", 
													"Somalia", 
													"South Africa", 
													"South Georgia", 
													"South Sandwich Islands", 
													"Spain", 
													"Sri Lanka", 
													"Sudan", 
													"Suriname", 
													"Svalbard", 
													"Swaziland", 
													"Sweden", 
													"Switzerland", 
													"Syrian Arab Republic", 
													"Taiwan", 
													"Tajikistan", 
													"Tanzania", 
													"Thailand", 
													"The Grenadines", 
													"Timor-Leste", 
													"Tobago", 
													"Togo", 
													"Tokelau", 
													"Tonga", 
													"Trinidad", 
													"Tunisia", 
													"Turkey", 
													"Turkmenistan", 
													"Turks Islands", 
													"Tuvalu", 
													"Uganda", 
													"Ukraine", 
													"United Arab Emirates", 
													"United Kingdom", 
													"United States", 
													"Uruguay", 
													"US Minor Outlying Islands", 
													"Uzbekistan", 
													"Vanuatu", 
													"Vatican City State", 
													"Venezuela", 
													"Vietnam", 
													"Virgin Islands (British)", 
													"Virgin Islands (US)", 
													"Wallis", 
													"Western Sahara", 
													"Yemen", 
													"Zambia", 
													"Zimbabwe"
												 )'
						);*/
		$this->addColumn('user', 'bio_statement', 'text');
		$this->addColumn('user', 'send_confirmation', 'boolean DEFAULT false');
		$this->addColumn('user', 'is_reader', 'boolean DEFAULT false');
		$this->addColumn('user', 'is_author', 'boolean DEFAULT false');
		$this->addColumn('user', 'is_reviewer', 'boolean DEFAULT false');
		$this->addColumn('user', 'reviewer_interests', 'text');
		$this->addColumn('user', 'user_image', 'varchar(255)');
		$this->addColumn('user', 'last_login', 'datetime');
		$this->addColumn('user', 'is_deleted', 'boolean DEFAULT false');
	}

	public function down()
	{
		$this->dropColumn('user', 'salutation');
		$this->dropColumn('user', 'middle_name');
		$this->dropColumn('user', 'initials');
		$this->dropColumn('user', 'gender');
		$this->dropColumn('user', 'affiliation');
		$this->dropColumn('user', 'signature');
		$this->dropColumn('user', 'orcid_id');
		$this->dropColumn('user', 'url');
		$this->dropColumn('user', 'phone');
		$this->dropColumn('user', 'fax');
		$this->dropColumn('user', 'mailing_address');
		$this->dropColumn('user', 'country');
		$this->dropColumn('user', 'bio_statement');
		$this->dropColumn('user', 'send_confirmation');
		$this->dropColumn('user', 'is_reader');
		$this->dropColumn('user', 'is_author');
		$this->dropColumn('user', 'is_reviewer');
		$this->dropColumn('user', 'reviewer_interests');
		$this->dropColumn('user', 'user_image');
		$this->dropColumn('user', 'last_login');
		$this->dropColumn('user', 'is_deleted');
	}
}
