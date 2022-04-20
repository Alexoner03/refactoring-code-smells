<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * THE VIDEO CONTROLLER
 * © CodelyTV 2017
 */
class VideoController extends BaseController
{
    /**
     * Method used to create a new video
     * @todo Validate the request
     */
    public function postVideoAction(Request $request)
    {
        // Preparing the sql to create the video
        $title = $request->get('title');
        $url = $request->get('url');
        $courseId = $request->get('course_id');
        $connection = $this->getDoctrine()->getConnection();

        list($title, $videoId) = $this->createVideo($title, $url, $courseId, $connection);

        // And we return the video created
        return [
            'id'        => $videoId,
            'title'     => $title,
            'url'       => $url,
            'course_id' => $courseId,
        ];
    }

    private function sanitizeTitle(string $title): string
    {
        if (strpos($title, "hexagonal")) {
            $title = str_replace("hexagonal", "Hexagonal", $title);
        }
        if (strpos($title, "solid")) {
            $title = str_replace("solid", "SOLID", $title);
        }
        if (strpos($title, "tdd")) {
            $title = str_replace("tdd", "TDD", $title);
        }
        return $title;
    }

    private function createVideo($title, $url, $courseId, object $connection): array
    {
        $title = $this->sanitizeTitle($title);

        $sql = "INSERT INTO video (title, url, course_id) 
                VALUES (\"{$title}\",
                        \"{$url}\",
                        {$courseId}
                )";

        // Prepare doctrine statement
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        // IMPORTANT: Obtaining the video id. Take care, it's done without another query :)
        $videoId = $connection->lastInsertId();
        return array($title, $videoId);
    }
}
