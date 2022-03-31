<?php
class DB
{
    private const CHANNELS = [
        "telegram" => "Telegram, ohne Telefonnummer (empfohlen)",
        "skype" => "Skype (empfohlen)",
        "telegram_phone" => "Telegram",
        "phone" => "Telefon",
        "facetime" => "Facetime",
        "whatsapp" => "WhatsApp"
    ];

    private const LOCATIONS = [
        "support_ausl_amt",
        "support_doctor",
        "support_education",
        "support_amt",
        "support_other"
    ];

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var mysqli
     */
    private $connection;

    public function __construct()
    {
        $this->configuration = json_decode(file_get_contents("config.json"), true);
        $this->connection = mysqli_connect(
            $this->configuration['host'],
            $this->configuration['user'],
            $this->configuration['pass'],
            $this->configuration['db']
        );
    }

    public function getUsers($where = "")
    {

    }

    public function getAvailableChannels($hour = false, $weekDay = false)
    {
        if (!$hour) {
            $hour = $this->getHour();
        }
        if (!$weekDay) {
            $weekDay = $this->getWeekDay();
        }

        $where1 = $this->getFilterColumnByHour($hour) . " LIKE '%" . $this->convertWeekDayToString($weekDay) . "%'";

        $returnInformation = [];
        $where2 = "support_ausl_amt = 'Ja'";
        $returnInformation['support_ausl_amt'] = $this->getChannelsForCondition($where1. " AND ".$where2);

        $where2 = "support_doctor = 'Ja'";
        $returnInformation['support_doctor'] = $this->getChannelsForCondition($where1. " AND ".$where2);

        $where2 = "support_education = 'Ja'";
        $returnInformation['support_education'] = $this->getChannelsForCondition($where1. " AND ".$where2);

        $where2 = "support_amt = 'Ja'";
        $returnInformation['support_amt'] = $this->getChannelsForCondition($where1. " AND ".$where2);

        $where2 = "support_other = 'Ja'";
        $returnInformation['support_other'] = $this->getChannelsForCondition($where1. " AND ".$where2);

        return json_encode($returnInformation);
    }

    public function getContact(string $channel, string $location): string
    {
        if (!in_array($location, self::LOCATIONS)) {
            die();
        }

        $where1 = $this->getFilterColumnByHour($this->getHour()) . " LIKE '%" . $this->convertWeekDayToString($this->getWeekDay()) . "%'";

        $channelLabel = self::CHANNELS[$channel];
        $where = $location ." = 'Ja' and messengers LIKE '%".$channelLabel."%' AND ".$where1;
        $data = $this->connection->query("SELECT * FROM users WHERE ". $where);
        $data = $data->fetch_all(MYSQLI_ASSOC);

        $contact = $data[rand(0, count($data) - 1)];
        if ($contact) {
            $contactName = [];
            $contactName['name'] = $contact['nickname'];
            switch ($channel) {
                case "telegram":
                    $contactValue = $contact['telegram'] ?: $this->findClosestExistsContact($data, 'telegram');
                    $contactName['telegram'] = '<a href="https://t.me/'.str_replace("@", "", $contactValue).'">Telegram</a>';
                    break;
                case "skype":
                    $contactValue = $contact['skype'] ?: $this->findClosestExistsContact($data, 'skype');
                    $contactName['skype'] = '<a href="skype:'.$contactValue.'?call">Skype</a>';
                    break;
                case "telegram_phone":
                    $contactValue = $contact['telephone'] ?: $this->findClosestExistsContact($data, 'telephone');
                    $contactName['telegram_phone'] = $contactValue;
                    break;
                case "phone":
                    $contactValue = $contact['telephone'] ?: $this->findClosestExistsContact($data, 'telephone');
                    $contactName['phone'] = '<a href="tel:'.$contact['telephone'].'">'.$contactValue.'</a>';;
                    break;
                case "facetime":
                    $contactValue = $contact['telephone'] ?: $this->findClosestExistsContact($data, 'telephone');
                    $contactName['facetime'] = $contactValue;
                    break;
                case "whatsapp":
                    $contactValue = $contact['telephone'] ?: $this->findClosestExistsContact($data, 'telephone');
                    $contactName['whatsapp'] = '<a href="https://wa.me/'.$contactValue.'">WhatsApp</a>';
                    break;
            }
            return json_encode($contactName);
        }
        return "";
    }

    /**
     * Get Current Hour / German Time
     *
     * @return int
     */
    private function getHour(): int
    {
        return (int) date("H") + 1;
    }

    /**
     * Get current Weekday
     *
     * @return int
     */
    private function getWeekDay(): int
    {
        return (int) date("w");
    }

    private function getChannelsForCondition($condition)
    {

        $data = $this->connection->query("SELECT languages, messengers FROM users WHERE ". $condition);
        $data = $data->fetch_all();

        $channels = [];
        $languages = [];
        foreach ($data as $user) {
            $languages = array_merge($languages, explode(";", $user[0]));
            $channels = array_merge($channels, explode(";", $user[1]));
        }

        $channelKeys = [];
        foreach ($channels as $channel) {
            $channelKeys[array_search($channel, self::CHANNELS)] = $channel;
        }

        return ['channels' => $channelKeys, 'languages' => $languages];
    }

    private function getFilterColumnByHour(int $hour): string
    {
        if ($hour < 8) {
            return "time_early";
        }
        if ($hour >= 20) {
            return "time_later";
        }
        return "time_".$hour."_". ($hour + 1);
    }

    private function convertWeekDayToString(int $week): string
    {
        switch ($week) {
            case 0:
                return "Sonntag";
            case 1:
                return "Montag";
            case 2:
                return "Dienstag";
            case 3:
                return "Mittwoch";
            case 4:
                return "Donnerstag";
            case 5:
                return "Freitag";
            case 6:
                return "Samstag";
        }

        return 'unknown';
    }

    /**
     * Search for first exists contact type in dataset
     *
     * @param array $data
     * @param string $type
     * @return string|null
     */
    private function findClosestExistsContact(array $data, string $type): ?string
    {
        shuffle($data);
        $result = null;
        foreach ($data as $item) {
            if (isset($item[$type]) && $item[$type]) {
                $result = $item[$type];
                break;
            }
        }

        return $result;
    }

}

