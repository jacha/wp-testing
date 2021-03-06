<?php

class WpTesting_Model_Scale extends WpTesting_Model_AbstractTerm
{

    private $minimum = null;
    private $maximum = null;
    private $value   = null;

    /**
     * Sets scale range to non-null integer values
     *
     * @param integer $minimum
     * @param integer $maximum
     * @throws InvalidArgumentException
     * @return WpTesting_Model_Scale
     */
    public function setRange($minimum, $maximum)
    {
        if ($minimum instanceof WpTesting_Model_Scale) {
            $another = $minimum;
            $minimum = $another->minimum;
            $maximum = $another->maximum;
        }
        $minimum = $this->filterValue($minimum, 'minimum');
        $maximum = $this->filterValue($maximum, 'maximum');
        if (!($minimum < $maximum)) {
            throw new InvalidArgumentException('Scale minimum ' . $minimum . ' must be less than maximum ' . $maximum);
        }
        if (!is_null($this->value) && !($minimum <= $this->value) || !($this->value <= $maximum)) {
            throw new InvalidArgumentException('Scale range from ' . $minimum . ' to ' . $maximum . ' must include value ' . $this->value);
        }
        $this->minimum = $minimum;
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * Sets scale range from another scale
     *
     * @param WpTesting_Model_Scale $another
     * @throws InvalidArgumentException
     * @return WpTesting_Model_Scale
     */
    public function extractRangeFrom(WpTesting_Model_Scale $another)
    {
        return $this->setRange($another->minimum, $another->maximum);
    }

    public function setValue($value)
    {
        $value = $this->filterValue($value, 'value');
        if (!($this->minimum <= $value) || !($value <= $this->maximum)) {
            throw new InvalidArgumentException('Scale value ' . $value . ' must be within range from ' . $this->minimum . ' to ' . $this->maximum);
        }
        $this->value = $value;
        return $this;
    }

    /**
     * @return number
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return number
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @return number
     */
    public function getSum()
    {
        if (is_null($this->maximum)) {
            return null;
        }
        return $this->maximum + min(array($this->minimum, 0));
    }

    /**
     * @return float
     */
    public function getValueAsRatio()
    {
        if (empty($this->maximum)) {
            return 0;
        }
        return round($this->value / $this->maximum, 2);
    }

    /**
     * @return string
     */
    public function getValueAsPercentage()
    {
        return sprintf("%u%%", $this->getValueAsRatio() * 100);
    }

    /**
     * @param mixed $value
     * @param string $name
     * @throws InvalidArgumentException
     * @return number
     */
    private function filterValue($value, $name)
    {
        if (is_null($value)) {
            throw new InvalidArgumentException('Scale ' . $name . ' must be not null');
        }
        if (!is_numeric($value)) {
            throw new InvalidArgumentException('Scale ' . $name . ' ' . $value . ' must be numeric');
        }
        if (intval($value) != $value) {
            throw new InvalidArgumentException('Scale ' . $name . ' ' . $value . ' must be integer');
        }
        return intval($value);
    }
}
