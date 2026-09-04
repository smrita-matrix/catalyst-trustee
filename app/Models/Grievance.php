<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grievance extends Model
{
    use HasFactory;

    protected $table = 'grievances';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'full_name',
        'pan',
        'email',
        'mobile',
        'address',
        'issuer_name',
        'series_name',
        'isin',
        'bonds_held',
        'investment_details',
        'nature_of_complaint',
        'complaint_types',
        'complaint_details',
        'is_read',
        'ip_address',
        'created_at',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'complaint_types' => 'array',
    ];

    /** The two forms on the grievance page. */
    public const TYPES = [
        'sebi'     => 'For Services Regulated By SEBI',
        'non_sebi' => 'For Services Not Regulated By SEBI',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? self::TYPES['non_sebi'];
    }

    /**
     * The details to list in an email, for whichever form this came from.
     *
     * The two forms ask for different things, so building the list here keeps
     * every email showing only the fields that were actually filled in -
     * rather than a fixed table with blank rows for the other form's questions.
     *
     * @param  bool  $forAdmin  the team also needs the sender's contact details
     * @return array<string, string>
     */
    public function summaryRows(bool $forAdmin = false): array
    {
        $rows = [];

        if ($forAdmin) {
            $rows['Full Name'] = (string) $this->full_name;
            $rows['Email']     = (string) $this->email;
            $rows['Mobile']    = (string) $this->mobile;
            $rows['PAN']       = (string) $this->pan;
        }

        if ($this->type === 'sebi') {
            $rows['Name of Issuer']      = (string) $this->issuer_name;
            $rows['ISIN Number']         = (string) $this->isin;
            $rows['Investment Details']  = (string) $this->investment_details;
            $rows['Nature of Complaint'] = (string) $this->nature_of_complaint;
        } else {
            $rows['Debenture Issuer']      = (string) $this->issuer_name;
            $rows['Debenture Series']      = (string) $this->series_name;
            $rows['ISIN / Multiple ISIN']  = (string) $this->isin;
            $rows['No of Bonds Held']      = (string) $this->bonds_held;
            $rows['Complaint Particulars'] = implode(', ', (array) $this->complaint_types);
            $rows['Details of Grievance']  = (string) $this->complaint_details;

            if ($forAdmin) {
                $rows['Postal Address'] = (string) $this->address;
            }
        }

        // A blank row tells the reader nothing, so leave it out.
        return array_filter($rows, fn ($v) => trim($v) !== '');
    }

    public function scopeSebi($query)
    {
        return $query->where('type', 'sebi');
    }

    public function scopeNonSebi($query)
    {
        return $query->where('type', 'non_sebi');
    }
}
