<?php

//รายละเอียดข้อมูลรถ
$route['masViewCar/(:any)']                 = 'service/car/CarDetail_controller/index/$1';

// calendar (ช่องทางให้บริการ)
$route['masCLDView/(:any)/(:any)']          = 'service/calendar/Calendar_controller/index/$1/$2';
$route['calendarList']                      = 'service/calendar/Calendar_controller/FSvCCLDListPage';
$route['calendarDataTable']                 = 'service/calendar/Calendar_controller/FSvCCLDDataList';
$route['calendarPageAdd']                   = 'service/calendar/Calendar_controller/FSvCCLDAddPage';
$route['calendarEventAdd']                  = 'service/calendar/Calendar_controller/FSaCCLDAddEvent';
$route['calendarPageEdit']                  = 'service/calendar/Calendar_controller/FSvCCLDEditPage';
$route['calendarEventEdit']                 = 'service/calendar/Calendar_controller/FSaCCLDEditEvent';
$route['calendarEventDelete']               = 'service/calendar/Calendar_controller/FSaCCLDDeleteEvent';

// CalendarUser
$route ['calendaruserDataTable']            = 'service/calendar/Calendar_controller/FSvCCLDUserDataList';
$route ['calendaruserPageAdd']              = 'service/calendar/Calendar_controller/FSvCCLDUserCalendarAddPage';
$route ['calendaruserEventAdd']             = 'service/calendar/Calendar_controller/FSoCCLDUserCalendarAddEvent';
$route ['calendaruserPageEdit']             = 'service/calendar/Calendar_controller/FSvCCLDUserCalendarEditPage';
$route ['calendaruserEventEdit']            = 'service/calendar/Calendar_controller/FSoCCLDUserCalendarEditEvent';
$route ['calendaruserEventDelete']          = 'service/calendar/Calendar_controller/FSoCCLDDeleteCalendarUserEvent';

// QasSubGroup (กลุ่มย่อยชุดข้อมูล / กลุ่มย่อยคำถาม)
$route['masQSGView/(:any)/(:any)']          = 'service/qassubgroup/Qassubgroup_controller/index/$1/$2';
$route['qassubgroupList']                   = 'service/qassubgroup/Qassubgroup_controller/FSvCQSGListPage';
$route['qassubgroupDataTable']              = 'service/qassubgroup/Qassubgroup_controller/FSvCQSGDataList';
$route['qassubgroupPageAdd']                = 'service/qassubgroup/Qassubgroup_controller/FSvCQSGAddPage';
$route['qassubgroupEventAdd']               = 'service/qassubgroup/Qassubgroup_controller/FSaCQSGAddEvent';
$route['qassubgroupPageEdit']               = 'service/qassubgroup/Qassubgroup_controller/FSvCQSGEditPage';
$route['qassubgroupEventEdit']              = 'service/qassubgroup/Qassubgroup_controller/FSaCQSGEditEvent';
$route['qassubgroupEventDelete']            = 'service/qassubgroup/Qassubgroup_controller/FSaCQSGDeleteEvent';

// QasGroup (กลุ่มชุดข้อมูล / กลุ่มคำถาม)
$route['masQGPView/(:any)/(:any)']          = 'service/qasgroup/Qasgroup_controller/index/$1/$2';
$route['qasgroupList']                      = 'service/qasgroup/Qasgroup_controller/FSvCQGPListPage';
$route['qasgroupDataTable']                 = 'service/qasgroup/Qasgroup_controller/FSvCQGPDataList';
$route['qasgroupPageAdd']                   = 'service/qasgroup/Qasgroup_controller/FSvCQGPAddPage';
$route['qasgroupEventAdd']                  = 'service/qasgroup/Qasgroup_controller/FSaCQGPAddEvent';
$route['qasgroupPageEdit']                  = 'service/qasgroup/Qasgroup_controller/FSvCQGPEditPage';
$route['qasgroupEventEdit']                 = 'service/qasgroup/Qasgroup_controller/FSaCQGPEditEvent';
$route['qasgroupEventDelete']               = 'service/qasgroup/Qasgroup_controller/FSaCQGPDeleteEvent';

// Message
$route['masMSGView/(:any)/(:any)']          = 'service/message/Message_controller/index/$1/$2';
$route['messageList']                       = 'service/message/Message_controller/FSvCMSGListPage';
$route['messageDataTable']                  = 'service/message/Message_controller/FSvCMSGDataList';
$route['messagePageAdd']                    = 'service/message/Message_controller/FSvCMSGAddPage';
$route['messageEventAdd']                   = 'service/message/Message_controller/FSaCMSGAddEvent';
$route['messagePageEdit']                   = 'service/message/Message_controller/FSvCMSGEditPage';
$route['messageEventEdit']                  = 'service/message/Message_controller/FSaCMSGEditEvent';
$route['messageEventDelete']                = 'service/message/Message_controller/FSaCMSGDeleteEvent';

// Car
$route['masCARView/(:any)/(:any)']          = 'service/car/Car_controller/index/$1/$2';
$route['carList']                           = 'service/car/Car_controller/FSvCCARListPage';
$route['carDataTable']                      = 'service/car/Car_controller/FSvCCARDataList';
$route['carPageAdd']                        = 'service/car/Car_controller/FSvCCARAddPage';
$route['carEventAdd']                       = 'service/car/Car_controller/FSaCCARAddEvent';
$route['carPageEdit']                       = 'service/car/Car_controller/FSvCCAREditPage';
$route['carEventEdit']                      = 'service/car/Car_controller/FSaCCAREditEvent';
$route['carEventDelete']                    = 'service/car/Car_controller/FSaCCARDeleteEvent';
$route['carHistoryDataTable']               = 'service/car/Car_controller/FSvCCARHistoryDataList';
$route['carOrderHistoryDataTable']          = 'service/car/Car_controller/FSvCCAROrderHistoryDataList';
$route['carSaleHistoryDataTable']           = 'service/car/Car_controller/FSvCCARSaleHistoryDataList';

// Question
$route['masQAHView/(:any)/(:any)']          = 'service/question/Question_controller/index/$1/$2';
$route['questionList']                      = 'service/question/Question_controller/FSvCQAHListPage';
$route['questionDataTable']                 = 'service/question/Question_controller/FSvCQAHDataList';
$route['questionPageAdd']                   = 'service/question/Question_controller/FSvCQAHAddPage';
$route['questionEventAdd']                  = 'service/question/Question_controller/FSaCQAHAddEvent';
$route['questionPageEdit']                  = 'service/question/Question_controller/FSvCQAHEditPage';
$route['questionPagePreview']               = 'service/question/Question_controller/FSvCQAHPreviewPage';
$route['questionEventEdit']                 = 'service/question/Question_controller/FSaCQAHEditEvent';
$route['questionEventDelete']               = 'service/question/Question_controller/FSaCQAHDeleteEvent';

// QuestionDetail
$route ['questiondetailEventAdd']           = 'service/question/Question_controller/FSoCQAHQuestionDetailAddEvent';
$route ['questiondetailPageEdit']           = 'service/question/Question_controller/FSvCQAHQuestionDetailEditPage';
$route ['questiondetailEventEdit']          = 'service/question/Question_controller/FSoCQAHQuestionDetailEditEvent';
$route ['questiondetailEventDelete']        = 'service/question/Question_controller/FSoCQAHDeleteQuestionDetailEvent';
$route ['questiondetailDataTable']          = 'service/question/Question_controller/FSvCQAHQurstionDetailDataList';
$route ['questiondetailPageAdd']            = 'service/question/Question_controller/FSvCCQAHQuestionDetailAddPage';

// lot
$route['maslot/(:any)/(:any)']                  = 'service/pdtlot/cPdtLot/index/$1/$2';
$route['maslotList']                            = 'service/pdtlot/cPdtLot/FSvCLotListPage';
$route['maslotDataTable']                       = 'service/pdtlot/cPdtLot/FSvCLotDataList';
$route['maslotPageAdd']                         = 'service/pdtlot/cPdtLot/FSvCLotAddPage';
$route['maslotPageEdit']                        = 'service/pdtlot/cPdtLot/FSvCLotEditPage';
$route['maslotEventAdd']                        = 'service/pdtlot/cPdtLot/FSoCLotAddEvent';
$route['maslotEventEdit']                       = 'service/pdtlot/cPdtLot/FSoCLotEditEvent';
$route['maslotEventDelete']                     = 'service/pdtlot/cPdtLot/FSoCLotDeleteEvent';
$route['maslotBAMDataTable']                    = 'service/pdtlot/cPdtLot/FSvCLotBAMDataTable';
$route['maslotBAMEventDelete']                  = 'service/pdtlot/cPdtLot/FSvCLotBAMDeleteEvent';
$route['maslotBAMEventDeleteMulti']             = 'service/pdtlot/cPdtLot/FSvCLotBAMDeleteEventMulti';
$route['maslotBAMPageAdd']                      = 'service/pdtlot/cPdtLot/FSvCLotBAMAddPage';
$route['maslotBAMPageEdit']                     = 'service/pdtlot/cPdtLot/FSvCLotBAMEditPage';
$route['maslotBAMEventAdd']                     = 'service/pdtlot/cPdtLot/FSvCLotBAMAddEvent';
$route['maslotBAMEventEdit']                    = 'service/pdtlot/cPdtLot/FSvCLotBAMEditEvent';


//Overduel
$route['masOdl/(:any)/(:any)']  = 'service/overduel/cOverDuel/index/$1/$2';
$route['masOdlList']            = 'service/overduel/cOverDuel/FSvCOdlListPage';
$route['masOdlDataTable']       = 'service/overduel/cOverDuel/FSvCOdlDataList';
$route['masOdlPageAdd']         = 'service/overduel/cOverDuel/FSvCOdlAddPage';
$route['masOdlPageEdit']        = 'service/overduel/cOverDuel/FSvCOdlEditPage';
$route['masOdlEventAdd']        = 'service/overduel/cOverDuel/FSoCOdlAddEvent';
$route['masOdlEventEdit']       = 'service/overduel/cOverDuel/FSoCOdlEditEvent';
$route['masOdlEventDelete']     = 'service/overduel/cOverDuel/FSoCOdlDeleteEvent';
$route['masOdlChkDupMinMax']    = 'service/overduel/cOverDuel/FSnCOdlChkDupMinMax';
