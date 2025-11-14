<?php
namespace App\Exports; 
 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
 
class UserExport implements FromCollection, WithHeadings{ 
    use Exportable;
    public function collection(){  
        $courses    =   Course::join('lms_schools', 'lms_schools.id',  'lms_course.school_id')
                                ->where('lms_schools.status', 0)
                                ->where('lms_course.deleted', 0)
                                ->select('lms_schools.name as school_name', 
                                        'lms_course.id', 
                                        'lms_course.title',  
                                        'lms_course.created_at', 
                                        'lms_schools.language', 
                                        'lms_course.slug')
                                ->orderBy('lms_course.created_at')
                                ->get()
                ->map(function ($course) {
                    $result =   array(
                                    'school_name' => $course->school_name,
                                    'id'          => $course->id,
                                    'title'       => $course->title,
                                    'created_at'  => $course->created_at,
                                    'language'    => $course->language,
                                );
                    if( Mainhelper::checkAvailableLocale($course->language)){
                        $result['link'] =    route($course->language . '.learn.course.detail', [$course->id, $course->slug]); 
                    }
                    else{
                        $result['link'] =    route('en.learn.course.detail', [$course->id, $course->slug]); 
                    }
                    return   $result;
                });

        return $courses; 
    }

    public function headings(): array{
        return ['School', 'ID', 'Title', 'Date', 'Language', 'Link'];
    }
}
