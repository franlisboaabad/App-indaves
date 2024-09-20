<table>
    <td colspan="12" align="center"
        style="background: #92c3da; color: #1a1919;font-size:14px;text-align:center;text-transform: uppercase">
        <strong>{{$title }}</strong></td>
</table>

<div style="margin-top:20px; margin-bottom:20px;">
    <table border="1">
        <tr>
            <td colspan="9" style="text-align: right">
                <strong>F. REPORTE: </strong>
            </td>
            <td style="text-align: center">{{date('d-m-Y H:i A')}}</td>
        </tr>
        <tr>
            @isset($empresa)
                <td>
                    <strong>EMPRESA: </strong>
                </td>
                <td colspan="10">{{$empresa?->name}}</td>
            @endif
        </tr>
    </table>
</div>
