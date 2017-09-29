<?php
namespace Home\Model;

use Think\Model\RelationModel;

class CandidateModel extends RelationModel
{
    protected $pk = 'candidate_id';
    // 定义关联模型
    protected $_link = array(
        'cv'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'cv',
            'mapping_name'  => 'cv',
            'foreign_key'   => 'candidate_id',
            ),
        'phone'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'phone',
            'foreign_key'   => 'candidate_id',
            'mapping_name'  => 'phone',
            ),
        'grp'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'grp',
            'foreign_key'   => 'candidate_id',
            'mapping_name'  => 'grp',
            ),    
        'onsite1'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'onsite1',
            'foreign_key'   => 'candidate_id',
            'mapping_name'  => 'onsite1',
            ),
        'onsite2'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'onsite2',
            'foreign_key'   => 'candidate_id',
            'mapping_name'  => 'onsite2',
            ),
        'onsite3'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'onsite3',
            'foreign_key'   => 'candidate_id',
            'mapping_name'  => 'onsite3',
            ),
        );
    protected $_scope = array(
        // personal info
        'info'=>array(
            'field'=>'candidate_id,name_en,name_cn,status,service_line,if_group,gender,phone,email,location,assign_date
            degree,channel,recommender,receive_date,university,graduation_date,major',
        ),
        // 命名范围latest
        'cv'=>array(
            'field'=>'cv_interviewer,cv_status,cv_date,cv_sum,cv_passed,cv_score1,cv_score2,cv_score3,cv_score4,
            cv_score5,cv_score6,cv_score7,cv_score8,cv_score9,cv_comment'
        ),
        'phone'=>array(
            'field'=>'phone_interviewer,phone_status,phone_date,phone_sum,phone_passed,phone_score1,phone_score2,phone_score3,phone_score4,
            phone_score5,phone_score6,phone_score7,phone_score8,phone_score9,phone_comment'
        ),
        'group'=>array(
            'field'=>'group_interviewer,group_status,group_date,group_sum,group_passed,group_score1,group_score2,group_score3,group_score4,
            group_score5,group_score6,group_score7,group_score8,group_score9,group_comment'
        ),
        'onsite1'=>array(
            'field'=>'onsite1_interviewer,onsite1_status,onsite1_date,onsite1_sum,onsite1_passed,onsite1_score1,onsite1_score2,onsite1_score3,onsite1_score4,
            onsite1_score5,onsite1_score6,onsite1_score7,onsite1_score8,onsite1_score9,onsite1_comment'
        ),
        'onsite2'=>array(
            'field'=>'onsite2_interviewer,onsite2_status,onsite2_date,onsite2_sum,onsite2_passed,onsite2_score1,onsite2_score2,onsite2_score3,onsite2_score4,
            onsite2_score5,onsite2_score6,onsite2_score7,onsite2_score8,onsite2_score9,onsite2_comment'
        ),
        'onsite3'=>array(
            'field'=>'onsite3_interviewer,onsite3_status,onsite3_date,onsite3_sum,onsite3_passed,onsite3_score1,onsite3_score2,onsite3_score3,onsite3_score4,
            onsite3_score5,onsite3_score6,onsite3_score7,onsite3_score8,onsite3_score9,onsite3_comment'
        ),
    );
}
