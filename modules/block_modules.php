<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block for displayed logged in user's course completion status.
 * Displays overall, and individual criteria status for logged in user.
 *
 * @package    block_modules
 * @copyright  2009-2012 Catalyst IT Ltd
 * @author     Aaron Barnes <aaronb@catalyst.net.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_modules extends block_base {

    public function init() {
        global $CFG;

        require_once($CFG->libdir . '/completionlib.php');

        $this->title = get_string('pluginname', 'block_modules');
    }

    public function applicable_formats() {
        return array('course-view' => true);
    }

    public function get_content() {
        global $DB, $USER;

        // If content is cached.
        if ($this->content !== null) {
            return $this->content;
        }

        $course = $this->page->course;
        $context = context_course::instance($course->id);

        // Create empty content.
        $this->content = new stdClass();
        $this->content->text = '';

        $table = new html_table();
        $table->head = array(
            get_string('moduleid', 'block_modules'),
            get_string('activityname', 'block_modules'),
            get_string('creationdate', 'block_modules'),
            get_string('status', 'block_modules'),
        );
        $table->data = array();

        $moduleinfo = $DB->get_records_sql("
            SELECT cm.id AS cmid,
                   cm.course AS courseid,
                   cm.module AS moduleid,
                   cm.added AS creationdate,
                   md.name AS modulename,
                   cm.instance AS instanceid,
                   cs.name AS sectionname,
                   cm.visible,
                   cm.completion
            FROM {course_modules} cm
            INNER JOIN {modules} md ON cm.module = md.id
            INNER JOIN {course_sections} cs ON cm.section = cs.id
            WHERE cm.course = :courseid",
            array('courseid' => $course->id)
        );

        foreach ($moduleinfo as $moduledata) {
            $url = new moodle_url('/mod/' . $moduledata->modulename . '/view.php', array('id' => $moduledata->cmid));
            // Create a link to the activity.
            $activitylink = html_writer::link($url, $moduledata->modulename);
            if($moduledata->completion == 1) {
                $status = 'completed';
            } else {
                $status = '';
            }
            $table->data[] = array(
                $moduledata->cmid,
                $activitylink,
                date('d-M-Y', $moduledata->creationdate),
                $status,
            );
        }
        $this->content->text .= html_writer::table($table);
        return $this->content;
    }
}