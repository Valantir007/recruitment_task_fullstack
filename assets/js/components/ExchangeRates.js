// ./assets/js/components/ExchangeRates.js

import React, {Component} from 'react';
import axios from 'axios';

class ExchangeRates extends Component {
    constructor(props) {
        super(props);

        this.date = new URLSearchParams(location.search).get('filter[date]');

        this.state = {
            loading: true,
            exchangeRates: [],
            date: (this.date != null && !isNaN(new Date(this.date))) ? new Date(this.date) : new Date(),
            message: null
        };
    }

    getBaseUrl() {
        return 'http://telemedi-zadanie.localhost';
    }

    componentDidMount() {
        this.getExchangeRates();
    }

    getExchangeRates() {
        const formatedDate = this.state.date.toISOString().split('T')[0];

        if (this.state.date.getDay() === 6 || this.state.date.getDay() === 0) { //wylaczenie sobót i niedziel
            this.setState({exchangeRates: [], loading: false, message: "NBP nie udostępnia kursu na ten dzień"});
            return;
        }
        axios.get(this.getBaseUrl() + `/api/exchange-rates?date=${formatedDate}`).then(response => {
            this.setState({exchangeRates: response.data, loading: false, message: null});
        }).catch(error => {
            this.setState({exchangeRates: [], loading: false, message: "Wystąpił nieoczekiwany błąd. Nasi specjaliści już nad tym pracują."});
            console.error(error);
        });
    }

    dateChanged(e) {
        this.setState({exchangeRates: [], loading: true, date: new Date(e.target.value)}, () => {
            window.history.replaceState(null, null, `/exchange-rates?filter[date]=${e.target.value}`);
            this.getExchangeRates();
        });
    }

    render() {
        console.log(this.state.date);
        const loading = this.state.loading;
        const exchangeRates = this.state.exchangeRates;
        const message = this.state.message;
        const defaultValue = this.state.date.toISOString().split('T')[0];

        return(
            <div>
                <section className="row-section">
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col-md-10 offset-md-1">
                                <h2>Kursy kupna i sprzedaży wybranych walut</h2>
                                <form className="form-inline" name="filter">
                                    <div className="form-group mx-sm-3 mb-2">
                                        <label htmlFor="date-form">Data notowania kursu:&nbsp;</label>
                                        <input type="date" name="filter[date]" max={new Date().toISOString().split('T')[0]} className="form-control" id="date-form" defaultValue={defaultValue} onChange={(e) => this.dateChanged(e)}/>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-10 offset-md-1">
                                {loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    message ? (
                                        <div className="alert alert-danger" role="alert">{message}</div>
                                    ) : (
                                        <table className="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kod waluty</th>
                                                <th>Nazwa waluty</th>
                                                <th>Kurs kupna (na dzień dzisiejszy)</th>
                                                <th>Kurs sprzedaży (na dzień dzisiejszy)</th>
                                                <th>Kurs kupna (na dzień {defaultValue ? defaultValue : 'dzisiejszy'})</th>
                                                <th>Kurs sprzedaży (na dzień {defaultValue ? defaultValue : 'dzisiejszy'})</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {exchangeRates.map((rate, i) => {
                                                return (
                                                    <tr key={i}>
                                                        <td>{rate.currencyCode}</td>
                                                        <td>{rate.currencyName}</td>
                                                        <td>{rate.todayBuyingRate}</td>
                                                        <td>{rate.todaySellingRate}</td>
                                                        <td>{rate.buyingRateByDate}</td>
                                                        <td>{rate.sellingRateByDate}</td>
                                                    </tr>
                                                )
                                            })}
                                            </tbody>
                                        </table>
                                    )
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    )
    }
}

export default ExchangeRates;
