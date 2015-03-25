<?php
/**
 * Description of Task.php
 * @copyright Copyright (c) THREE POINT PRODUCTIONS, LLC
 * @author    Egor Gerasimchuk <egerasimchuk@dev.football.com>
 */

namespace App\Models;

use App\User;

/**
 * An Eloquent Model: 'Task'
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string $url
 * @property string $filename
 * @property string $data
 * @property string $html
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $user
 */
class Task extends BaseModel
{

    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 10;
    const STATUS_COMPLETED = 20;
    const STATUS_FAILED = 30;

    /** @var string */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'user_id', 'status', 'filename', 'data', 'html'];

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->status == self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isProcessing()
    {
        return $this->status == self::STATUS_PROCESSING;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return $this->status == self::STATUS_FAILED;
    }

    public static function fromUrl($url)
    {
        $task = new self();
        return $task->create(['url' => $url, 'status' => self::STATUS_NEW]);
    }

} 