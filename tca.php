<?php
if (!defined ('TYPO3_MODE')) {
	die('Access denied.');
}

// unserialize the configuration array
$globalConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['seminars']);
$usePageBrowser = (boolean) $globalConfiguration['usePageBrowser'];
$selectTopicsFromAllPages = (boolean) $globalConfiguration['selectTopicsFromAllPages'];
$selectType = $usePageBrowser ? 'group' : 'select';
$selectWhereForTopics = ($selectTopicsFromAllPages) ? '' : ' AND tx_seminars_seminars.pid=###STORAGE_PID###';

$TCA['tx_seminars_seminars'] = array(
	'ctrl' => $TCA['tx_seminars_seminars']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,starttime,endtime,title,subtitle,description,accreditation_number,credit_points,begin_date,end_date,deadline_registration,place,room,speakers,price_regular,price_special,payment_methods,organizers,needs_registration,allows_multiple_registrations,attendees_min,attendees_max,cancelled,owner_feuser,vips,notes'
	),
	'columns' => array(
		'object_type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.object_type',
			'config' => array(
			'type' => 'radio',
				'default' => '0',
				'items' => array(
					array('LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.object_type.I.0', '0'),
					array('LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.object_type.I.1', '1'),
					array('LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.object_type.I.2', '2')
				)
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'default' => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'checkbox' => '0',
				'default' => '0',
				'range' => array(
					'upper' => mktime(0,0,0,12,31,2020),
					'lower' => mktime(0,0,0,date('m')-1,date('d'),date('Y'))
				)
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		),
		'topic' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.topic',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_seminars',
				'foreign_table' => 'tx_seminars_seminars',
				// only allow for topic records and complete event records, but not for date records
				'foreign_table_where' => 'AND (tx_seminars_seminars.object_type=0 '
					.'OR tx_seminars_seminars.object_type=1)'.$selectWhereForTopics
					.' ORDER BY title',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1
			)
		),
		'subtitle' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.subtitle',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'teaser' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.teaser',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.description',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'event_type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.event_type',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_event_types',
				'foreign_table' => 'tx_seminars_event_types',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'items' => array(
					'' => ''
				)
			)
		),
		'accreditation_number' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.accreditation_number',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'eval' => 'trim'
			)
		),
		'credit_points' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.credit_points',
			'config' => array(
				'type' => 'input',
				'size' => '3',
				'max' => '3',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'upper' => '999',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'begin_date' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.begin_date',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'datetime',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'end_date' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.end_date',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'datetime',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'deadline_registration' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.deadline_registration',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'datetime',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'deadline_early_bird' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.deadline_early_bird',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'datetime',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'place' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.place',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_sites',
				'foreign_table' => 'tx_seminars_sites',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_place_mm'
			)
		),
		'room' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.room',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'additional_times_places' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.additional_times_places',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'lodgings' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.lodgings',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_lodgings',
				'foreign_table' => 'tx_seminars_lodgings',
				'foreign_table_where' => 'ORDER BY title',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_lodgings_mm'
			)
		),
		'foods' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.foods',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_foods',
				'foreign_table' => 'tx_seminars_foods',
				'foreign_table_where' => 'ORDER BY title',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_foods_mm'
			)
		),
		'speakers' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.speakers',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_speakers',
				'foreign_table' => 'tx_seminars_speakers',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_speakers_mm'
			)
		),
		'partners' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.partners',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_speakers',
				'foreign_table' => 'tx_seminars_speakers',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_speakers_mm_partners'
			)
		),
		'tutors' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.tutors',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_speakers',
				'foreign_table' => 'tx_seminars_speakers',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_speakers_mm_tutors'
			)
		),
		'leaders' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.leaders',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_speakers',
				'foreign_table' => 'tx_seminars_speakers',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_speakers_mm_leaders'
			)
		),
		'price_regular' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.price_regular',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '8',
				'eval' => 'double2',
				'checkbox' => '0.00',
				'range' => array(
					'upper' => '99999.99',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'price_regular_early' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.price_regular_early',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '8',
				'eval' => 'double2',
				'checkbox' => '0.00',
				'range' => array(
					'upper' => '99999.99',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'price_regular_board' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.price_regular_board',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '8',
				'eval' => 'double2',
				'checkbox' => '0.00',
				'range' => array(
					'upper' => '99999.99',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'price_special' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.price_special',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '8',
				'eval' => 'double2',
				'checkbox' => '0.00',
				'range' => array(
					'upper' => '99999.99',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'price_special_early' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.price_special_early',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '8',
				'eval' => 'double2',
				'checkbox' => '0.00',
				'range' => array(
					'upper' => '99999.99',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'price_special_board' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.price_special_board',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '8',
				'eval' => 'double2',
				'checkbox' => '0.00',
				'range' => array(
					'upper' => '99999.99',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'additional_information' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.additional_information',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'checkboxes' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.checkboxes',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_checkboxes',
				'foreign_table' => 'tx_seminars_checkboxes',
				'foreign_table_where' => 'ORDER BY title',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_checkboxes_mm'
			)
		),
		'uses_terms_2' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.uses_terms_2',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'payment_methods' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.payment_methods',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_payment_methods',
				'foreign_table' => 'tx_seminars_payment_methods',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 999
			)
		),
		'organizers' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.organizers',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_organizers',
				'foreign_table' => 'tx_seminars_organizers',
				'size' => 5,
				'minitems' => 1,
				'maxitems' => 999
			)
		),
		'needs_registration' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.needs_registration',
			'config' => array(
				'type' => 'check',
				'default' => 1
			)
		),
		'allows_multiple_registrations' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.allows_multiple_registrations',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'attendees_min' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.attendees_min',
			'config' => array(
				'type' => 'input',
				'size' => '4',
				'max' => '4',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'upper' => '9999',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'attendees_max' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.attendees_max',
			'config' => array(
				'type' => 'input',
				'size' => '4',
				'max' => '4',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'upper' => '9999',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'cancelled' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.cancelled',
			'config' => array(
				'type' => 'check',
			)
		),
		'owner_feuser' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.owner_feuser',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			)
		),
		'vips' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.vips',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_seminars_feusers_mm'
			)
		),
		'notes' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.notes',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		)
	),
	'types' => array(
		// Single event
		'0' => array('showitem' => '' .
			'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelGeneral, object_type, hidden;;1;;1-1-1, title;;;;2-2-2, subtitle;;;;3-3-3, teaser, description;;;richtext[paste|bold|italic|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts_css], event_type, accreditation_number, credit_points, additional_information;;;richtext[paste|bold|italic|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts_css], checkboxes, uses_terms_2, cancelled, owner_feuser, vips, notes, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelPlaceTime, begin_date, end_date, deadline_registration, deadline_early_bird, place, room, additional_times_places, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelSpeakers, speakers, partners, tutors, leaders, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelOrganizers, organizers, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelAttendees, needs_registration, allows_multiple_registrations, attendees_min, attendees_max, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelLodging, lodgings, foods, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelPayment, price_regular, price_regular_early, price_regular_board, price_special, price_special_early, price_special_board, payment_methods'
		),
		// Multiple event topic
		'1' => array('showitem' =>
			'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelGeneral, object_type, hidden;;1;;1-1-1, title;;;;2-2-2, subtitle;;;;3-3-3, teaser, description;;;richtext[paste|bold|italic|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts_css], event_type, credit_points, additional_information;;;richtext[paste|bold|italic|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts_css], checkboxes, uses_terms_2, notes, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelAttendees, needs_registration, allows_multiple_registrations, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelPayment, price_regular, price_regular_early, price_regular_board, price_special, price_special_early, price_special_board, payment_methods'
		),
		// Multiple event date
		'2' => array('showitem' =>
			'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelGeneral, object_type, hidden;;1;;1-1-1, title;;;;2-2-2, topic, accreditation_number, cancelled, vips, notes, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelPlaceTime, begin_date, end_date, deadline_registration, deadline_early_bird, place, room, additional_times_places, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelSpeakers, speakers, partners, tutors, leaders, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelOrganizers, organizers, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelAttendees, attendees_min, attendees_max, '
				.'--div--;LLL:EXT:seminars/locallang_db.xml:tx_seminars_seminars.divLabelLodging, lodgings, foods'
		)
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime')
	)
);


$TCA['tx_seminars_speakers'] = array(
	'ctrl' => $TCA['tx_seminars_speakers']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title,organization,homepage,description,notes,address,phone_work,phone_home,phone_mobile,fax,email'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		),
		'organization' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.organization',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'homepage' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.homepage',
			'config' => array(
				'type' => 'input',
				'size' => '15',
				'max' => '255',
				'checkbox' => '',
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.description',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'picture' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.picture',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'gif,png,jpeg,jpg',
				'max_size' => 256,
				'uploadfolder' => 'uploads/tx_seminars',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1
			)
		),
		'notes' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.notes',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'address' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.address',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'phone_work' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.phone_work',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'phone_home' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.phone_home',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'phone_mobile' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.phone_mobile',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'fax' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.fax',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'email' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_speakers.email',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim,nospace'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2, organization;;;;3-3-3, homepage, description;;;richtext[paste|bold|italic|orderedlist|unorderedlist|link]:rte_transform[mode=ts_css], notes, address, phone_work, phone_home, phone_mobile, fax, email')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_seminars_attendances'] = array(
	'ctrl' => $TCA['tx_seminars_attendances']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title,user,seminar,price,seats,total_price,attendees_names,paid,datepaid,method_of_payment,account_number,bank_code,bank_name,account_owner,gender,name,address,zip,city,country,phone,email,been_there,interests,expectations,background_knowledge,accommodation,food,known_from,notes'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.title',
			'config' => array(
				'type' => 'none'
			)
		),
		'user' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.user',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1
			)
		),
		'seminar' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.seminar',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_seminars',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1
			)
		),
		'price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.price',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'seats' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.seats',
			'config' => array(
				'type' => 'input',
				'size' => '3',
				'max' => '3',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'upper' => '999',
					'lower' => '0'
				),
				'default' => '1'
			)
		),
		'total_price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.total_price',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '8',
				'eval' => 'double2',
				'checkbox' => '0.00',
				'range' => array(
					'upper' => '99999.99',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'attendees_names' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.attendees_names',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'kids' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.kids',
			'config' => array(
				'type' => 'input',
				'size' => '3',
				'max' => '3',
				'eval' => 'int',
				'checkbox' => '0',
				'range' => array(
					'upper' => '999',
					'lower' => '0'
				),
				'default' => '0'
			)
		),
		'paid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.paid',
			'config' => array(
				'type' => 'check'
			)
		),
		'datepaid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.datepaid',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '20',
				'eval' => 'date',
				'checkbox' => '0',
				'default' => '0'
			)
		),
		'method_of_payment' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.method_of_payment',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_payment_methods',
				'foreign_table' => 'tx_seminars_payment_methods',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'items' => array(
					'' => ''
				)
			)
		),
		'account_number' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.account_number',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'bank_code' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.bank_code',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'bank_name' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.bank_name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'account_owner' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.account_owner',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim'
			)
		),
		'gender' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.gender',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.gender.I.0', '0'),
					array('LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.gender.I.1', '1')
				),
				'size' => 1,
				'maxitems' => 1
			)
		),
		'name' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.name',
			'config' => array(
				'type' => 'input',
				'size' => '40',
				'max' => '80',
				'eval' => 'trim'
			)
		),
		'address' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.address',
			'config' => array(
				'type' => 'text',
				'cols' => '20',
				'rows' => '3'
			)
		),
		'zip' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.zip',
			'config' => array(
				'type' => 'input',
				'size' => '8',
				'max' => '10',
				'eval' => 'trim'
			)
		),
		'city' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.city',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '50',
				'eval' => 'trim'
			)
		),
		'country' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.country',
			'config' => array(
				'type' => 'input',
				'size' => '16',
				'max' => '40',
				'eval' => 'trim'
			)
		),
		'telephone' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.phone',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '20',
				'eval' => 'trim'
			)
		),
		'email' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.email',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '80',
				'eval' => 'trim'
			)
		),
		'been_there' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.been_there',
			'config' => array(
				'type' => 'check'
			)
		),
		'checkboxes' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.checkboxes',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_checkboxes',
				'foreign_table' => 'tx_seminars_checkboxes',
				'foreign_table_where' => 'ORDER BY title',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_attendances_checkboxes_mm'
			)
		),
		'interests' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.interests',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'expectations' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.expectations',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'background_knowledge' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.background_knowledge',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'lodgings' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.lodgings',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_lodgings',
				'foreign_table' => 'tx_seminars_lodgings',
				'foreign_table_where' => 'ORDER BY title',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_attendances_lodgings_mm'
			)
		),
		'accommodation' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.accommodation',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'foods' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.foods',
			'config' => array(
				'type' => $selectType,
				'internal_type' => 'db',
				'allowed' => 'tx_seminars_foods',
				'foreign_table' => 'tx_seminars_foods',
				'foreign_table_where' => 'ORDER BY title',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,
				'MM' => 'tx_seminars_attendances_foods_mm'
			)
		),
		'food' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.food',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'known_from' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.known_from',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'notes' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_attendances.notes',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'user;;;;1-1-1, seminar, price, seats, total_price, attendees_names, kids, paid, datepaid, method_of_payment;;2, name;;3, been_there, checkboxes, interests, expectations, background_knowledge, lodgings, accommodation, foods, food, known_from, notes')
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
		'2' => array('showitem' => 'account_number, bank_code, bank_name, account_owner'),
		'3' => array('showitem' => 'gender, address, zip, city, country, telephone, email')
	)
);



$TCA['tx_seminars_sites'] = array(
	'ctrl' => $TCA['tx_seminars_sites']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title,address,homepage,directions,notes'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_sites.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		),
		'address' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_sites.address',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'homepage' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_sites.homepage',
			'config' => array(
				'type' => 'input',
				'size' => '15',
				'max' => '255',
				'checkbox' => '',
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'directions' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_sites.directions',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'notes' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_sites.notes',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2, address;;;;3-3-3, homepage, directions;;;richtext[paste|bold|italic|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts_css], notes')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_seminars_organizers'] = array(
	'ctrl' => $TCA['tx_seminars_organizers']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title,homepage,email,email_footer'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_organizers.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		),
		'homepage' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_organizers.homepage',
			'config' => array(
				'type' => 'input',
				'size' => '15',
				'max' => '255',
				'checkbox' => '',
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'email' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_organizers.email',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim,nospace'
			)
		),
		'email_footer' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_organizers.email_footer',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5'
			)
		),
		'attendances_pid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_organizers.attendances_pid',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => '1',
				'maxitems' => '1',
				'minitems' => '0',
				'show_thumbs' => '1'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2, homepage;;;;3-3-3, email, email_footer, attendances_pid')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

$TCA['tx_seminars_payment_methods'] = array(
	'ctrl' => $TCA['tx_seminars_payment_methods']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title, description'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_payment_methods.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_payment_methods.description',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '10'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2, description')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

$TCA['tx_seminars_event_types'] = array(
	'ctrl' => $TCA['tx_seminars_event_types']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_event_types.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

$TCA['tx_seminars_checkboxes'] = array(
	'ctrl' => $TCA['tx_seminars_checkboxes']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_checkboxes.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

$TCA['tx_seminars_lodgings'] = array(
	'ctrl' => $TCA['tx_seminars_lodgings']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_lodgings.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

$TCA['tx_seminars_foods'] = array(
	'ctrl' => $TCA['tx_seminars_foods']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title'
	),
	'columns' => array(
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:seminars/locallang_db.xml:tx_seminars_foods.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'title;;;;2-2-2')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

?>
