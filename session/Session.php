<?php


namespace base\session;


use base\model\User;

class Session
{
    /**
     * @var string $id - идентификатор сессии
     * @var string $name - название сессии
     */
    private $id;
    private $name;

    /**
     * @var int $life - обычное время жизни сессии
     * @var int $rememberMe - более продолжительное время жизни, которое может
     * использоваться при авторизации с использованием флага "запомнить меня"
     */
    private $life = 86400;          // 1 день
    private $rememberMe = 604800;   // 1 неделя

    /**
     *  @var $user User
     *
     *  Объект хранит данные о пользователе, такие как login, email, телефон и т.п.
     */
    public $user;

    /**
     * @var $storage - хранилище данных сессии
     */
    private $storage;

    /**
     * @var array $lockedParams - параметры сессии, к которым нет прямого доступа.
     *
     *  Например, есть надстройка с базовым пользователем, которая работает с вложенным
     * массивом $_SESSION['user']. Таким образом, к параметру 'user' не должно быть
     * прямого доступа, только через соответсвующую ему обёртку.
     */
    private $lockedParams = [
        'user'
    ];

    /**
     *  Сессия активна по умолчанию, поэтому при создании класса в Page(),
     * сразу создаётся/восстанавливается значение сессии и её данные.
     *
     *  С помощью функции setBaseSettings() задаются базовые параметры для сессии,
     * такие как HttpOnly, время жизни и пр.
     */
    public function __construct()
    {
        $this->setBaseSettings();

        session_start();

        $this->id = session_id();
        $this->name = session_name();

        $this->user = new User();

        $this->storage = &$_SESSION;
    }

    /**
     *  Получение значения параметра сессии
     *
     * @param $param - название параметра
     * @return mixed - значение параметра
     */
    public function get($param)
    {
        if ($message = $this->checkLocked($param))
            exit($message);

        return $this->storage[$param];
    }

    /**
     *  Создаёт в сессии параметр со значением
     *
     * @param $param - название параметра
     * @param $value - значение параметра
     */
    public function set($param, $value)
    {
        if ($message = $this->checkLocked($param))
            exit($message);

        $this->storage[$param] = $value;
    }

    /**
     *  Удаляет переданный параметр из сессии.
     *
     * @param $param - название параметра для удаления
     */
    public function remove($param)
    {
        if ($this->has($param))
            unset($this->storage[$param]);
    }

    /**
     *  Проверяет наличие определённого параметра в сессии
     *
     * @param $param - название параметра для проверки
     * @return bool
     */
    public function has($param)
    {
        return isset($this->storage[$param]);
    }

    /**
     *  Удаляет все данные текущей сессии
     */
    public function close()
    {
        session_destroy();
    }

    /**
     *  Задаются базовые настройки сессии:
     * - HttpOnly для Cookie
     * - Максимальное время жизни сессии
     */
    public function setBaseSettings()
    {
        ini_set( 'session.cookie_httponly', 1 );
        ini_set('session.gc_maxlifetime', $this->life);
    }

    /**
     *  Проверяет сессию на существование
     *
     * @return bool
     */
    public function isActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     *  Устанавливаем более длительное значение жизни сессии. Можно использовать при авторизации пользователя, когда он
     * устанавливает флаг "запомнить меня"
     */
    public function rememberMe()
    {
        ini_set('session.gc_maxlifetime', $this->rememberMe);
    }

    /**
     * @param $param
     * @return string|null
     */
    private function checkLocked($param)
    {
        foreach ($this->lockedParams as $lockedParam) {
            if ($lockedParam == $param)
                return 'У вас нет прямого доступа к этой области хранилища.';
        }

        return null;
    }
}